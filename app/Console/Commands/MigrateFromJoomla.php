<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateFromJoomla extends Command
{
    protected $signature = 'joomla:migrate
                            {--dry-run : Recorre los datos sin escribir nada en la base destino}';

    protected $description = 'Migra contenido del Joomla viejo (conexión joomla_legacy) a las tablas Laravel.';

    /**
     * Base URL del Joomla vivo. Se usa para construir las URLs absolutas
     * de las imágenes durante esta primera fase. Después, el comando
     * joomla:download-images las baja localmente y reescribe el campo.
     */
    private const JOOMLA_BASE_URL = 'https://cooperativaliberte.coop';

    /**
     * Aliases de categorías "basura" del template original que NO se migran
     * aunque estén marcadas como com_content.
     */
    private const SKIP_CATEGORY_ALIASES = [
        'sample-pages',
        'features',
        'blog',
        'categoria-es-es',
        'category-en-gb',
        'uncategorised',
    ];

    private bool $dryRun = false;

    /**
     * Mapa joomla_id => laravel_id para categorías. Se llena durante
     * migrateCategories() y se usa en migrateArticle() para resolver
     * la category_id sin tocar la base. En dry-run usa un placeholder.
     */
    private array $categoryIdMap = [];

    /**
     * Mismo concepto que $categoryIdMap pero para usuarios autores.
     */
    private array $userIdMap = [];

    private array $stats = [
        'categories_created' => 0,
        'categories_updated' => 0,
        'categories_skipped' => 0,
        'users_created' => 0,
        'users_updated' => 0,
        'articles_created' => 0,
        'articles_updated' => 0,
        'articles_failed' => 0,
    ];

    public function handle(): int
    {
        $this->dryRun = (bool) $this->option('dry-run');

        if ($this->dryRun) {
            $this->warn('=== MODO DRY-RUN: no se escribirá nada en la base destino ===');
        }

        $this->info('Verificando conexión a joomla_legacy...');
        try {
            DB::connection('joomla_legacy')->getPdo();
        } catch (\Throwable $e) {
            $this->error('No se pudo conectar a joomla_legacy: '.$e->getMessage());
            return self::FAILURE;
        }
        $this->info('Conexión OK.');
        $this->newLine();

        if (! $this->dryRun && ! $this->confirm('Esto va a INSERTAR/ACTUALIZAR registros en la base local. ¿Continuar?', true)) {
            $this->warn('Cancelado por el usuario.');
            return self::SUCCESS;
        }

        $this->migrateCategories();
        $this->newLine();

        $this->migrateUsers();
        $this->newLine();

        $this->migrateArticles();
        $this->newLine();

        $this->showSummary();

        return self::SUCCESS;
    }

    /**
     * Migra categorías desde js4ox_categories.
     *
     * Procesa en orden de 'lft' (nested set) para garantizar que los padres
     * existan antes que los hijos cuando seteamos parent_id.
     */
    private function migrateCategories(): void
    {
        $this->info('Migrando categorías...');

        $rows = DB::connection('joomla_legacy')
            ->table('categories')
            ->where('extension', 'com_content')
            ->whereIn('language', ['es-ES', '*'])
            ->where('published', 1)
            ->orderBy('lft')
            ->get();

        $bar = $this->output->createProgressBar($rows->count());
        $bar->start();

        foreach ($rows as $row) {
            // Saltamos categorías de demo del template
            if (in_array($row->alias, self::SKIP_CATEGORY_ALIASES, true)) {
                $this->stats['categories_skipped']++;
                $bar->advance();
                continue;
            }

            // Saltamos la categoría ROOT (id=1, alias='root')
            if ($row->parent_id == 0 && $row->alias === 'root') {
                $this->stats['categories_skipped']++;
                $bar->advance();
                continue;
            }

            $parentLaravelId = null;
            if ($row->parent_id && $row->parent_id != 1) {
                // Buscamos el parent ya migrado por su joomla_id
                $parent = Category::where('joomla_id', $row->parent_id)->first();
                $parentLaravelId = $parent?->id;
            }

            $attributes = [
                'name'        => $row->title,
                'slug'        => $row->alias,
                'description' => $row->description ?: null,
                'parent_id'   => $parentLaravelId,
                'language'    => $row->language === '*' ? 'es' : substr($row->language, 0, 2),
                'sort_order'  => (int) $row->lft,
            ];

            if ($this->dryRun) {
                // Placeholder negativo para distinguirlo de IDs reales
                $this->categoryIdMap[$row->id] = -1 * (count($this->categoryIdMap) + 1);
                $this->stats['categories_created']++;
            } else {
                $existing = Category::where('joomla_id', $row->id)->first();
                if ($existing) {
                    $existing->update($attributes);
                    $this->categoryIdMap[$row->id] = $existing->id;
                    $this->stats['categories_updated']++;
                } else {
                    $created = Category::create(array_merge(['joomla_id' => $row->id], $attributes));
                    $this->categoryIdMap[$row->id] = $created->id;
                    $this->stats['categories_created']++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line(sprintf(
            '  Categorías: %d creadas, %d actualizadas, %d saltadas',
            $this->stats['categories_created'],
            $this->stats['categories_updated'],
            $this->stats['categories_skipped']
        ));
    }

    /**
     * Migra usuarios autores. Solo aquellos que tienen artículos publicados
     * en español. Los demás (sistema, demo) se ignoran.
     */
    private function migrateUsers(): void
    {
        $this->info('Migrando usuarios autores...');

        $authorIds = DB::connection('joomla_legacy')
            ->table('content')
            ->where('state', 1)
            ->where('language', 'es-ES')
            ->distinct()
            ->pluck('created_by');

        $rows = DB::connection('joomla_legacy')
            ->table('users')
            ->whereIn('id', $authorIds)
            ->get();

        foreach ($rows as $row) {
            $attributes = [
                'name'  => $row->name,
                'email' => $row->email,
            ];

            if ($this->dryRun) {
                $this->userIdMap[$row->id] = -1 * (count($this->userIdMap) + 1);
                $this->stats['users_created']++;
                continue;
            }

            $existing = User::where('joomla_id', $row->id)->first();
            if ($existing) {
                $existing->update($attributes);
                $this->userIdMap[$row->id] = $existing->id;
                $this->stats['users_updated']++;
            } else {
                $created = User::create(array_merge([
                    'joomla_id' => $row->id,
                    // Password aleatorio temporal. El usuario lo resetea
                    // con "olvidé mi contraseña" la primera vez que entra.
                    'password'  => Hash::make(Str::random(40)),
                ], $attributes));
                $this->userIdMap[$row->id] = $created->id;
                $this->stats['users_created']++;
            }
        }

        $this->line(sprintf(
            '  Usuarios: %d creados, %d actualizados (de %d candidatos)',
            $this->stats['users_created'],
            $this->stats['users_updated'],
            $rows->count()
        ));
    }

    /**
     * Migra los artículos publicados en español a la tabla news.
     */
    private function migrateArticles(): void
    {
        $this->info('Migrando artículos...');

        $rows = DB::connection('joomla_legacy')
            ->table('content')
            ->where('state', 1)
            ->where('language', 'es-ES')
            ->orderBy('id')
            ->get();

        $bar = $this->output->createProgressBar($rows->count());
        $bar->start();

        foreach ($rows as $row) {
            try {
                $this->migrateArticle($row);
            } catch (\Throwable $e) {
                $this->stats['articles_failed']++;
                $this->newLine();
                $this->error(sprintf(
                    'Falló artículo joomla_id=%d "%s": %s',
                    $row->id,
                    Str::limit($row->title, 60),
                    $e->getMessage()
                ));
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line(sprintf(
            '  Artículos: %d creados, %d actualizados, %d fallidos',
            $this->stats['articles_created'],
            $this->stats['articles_updated'],
            $this->stats['articles_failed']
        ));
    }

    private function migrateArticle(object $row): void
    {
        // Mapeo de categoría (resuelto desde el mapa en memoria)
        if (! isset($this->categoryIdMap[$row->catid])) {
            throw new \RuntimeException("Categoría joomla_id={$row->catid} no migrada (tal vez fue saltada).");
        }
        $categoryId = $this->categoryIdMap[$row->catid];

        // Mapeo de autor
        if (! isset($this->userIdMap[$row->created_by])) {
            throw new \RuntimeException("Autor joomla_id={$row->created_by} no migrado.");
        }
        $userId = $this->userIdMap[$row->created_by];

        // Cuerpo: introtext + fulltext (Joomla los separa por el "leer más")
        $body = trim(($row->introtext ?? '').($row->fulltext ?? ''));

        // Excerpt: primeros 280 caracteres del introtext sin HTML
        $excerpt = null;
        if ($row->introtext) {
            $plain = trim(strip_tags($row->introtext));
            $plain = preg_replace('/\s+/', ' ', $plain);
            $excerpt = Str::limit($plain, 280);
        }

        // Imagen destacada: parsear el JSON 'images' de Joomla
        $featuredImage = $this->extractFeaturedImage($row->images);

        // Fecha de publicación
        $publishedAt = $this->parseDate($row->publish_up) ?? $this->parseDate($row->created);

        $attributes = [
            'title'            => $row->title,
            'slug'             => $row->alias,
            'excerpt'          => $excerpt,
            'body'             => $body,
            'featured_image'   => $featuredImage,
            'category_id'      => $categoryId,
            'user_id'          => $userId,
            'status'           => 'published',
            'published_at'     => $publishedAt,
            'meta_title'       => $this->nullIfEmpty($row->metakey ?? null),
            'meta_description' => $this->nullIfEmpty($row->metadesc ?? null),
            'is_featured'      => (bool) $row->featured,
            'language'         => 'es',
        ];

        if ($this->dryRun) {
            $this->stats['articles_created']++;
            return;
        }

        $existing = News::where('joomla_id', $row->id)->first();
        if ($existing) {
            $existing->update($attributes);
            $this->stats['articles_updated']++;
        } else {
            News::create(array_merge(['joomla_id' => $row->id], $attributes));
            $this->stats['articles_created']++;
        }
    }

    /**
     * Parsea el campo 'images' de Joomla (JSON) y devuelve la URL absoluta
     * de la imagen destacada. Prefiere image_intro, fallback a image_fulltext.
     */
    private function extractFeaturedImage(?string $imagesJson): ?string
    {
        if (! $imagesJson) {
            return null;
        }

        $data = json_decode($imagesJson, true);
        if (! is_array($data)) {
            return null;
        }

        $path = $data['image_intro'] ?? $data['image_fulltext'] ?? null;
        if (! $path) {
            return null;
        }

        // Joomla a veces incluye query string ej: "images/foo.jpg#joomlaImage://..."
        $path = explode('#', $path)[0];
        $path = ltrim($path, '/');

        return self::JOOMLA_BASE_URL.'/'.$path;
    }

    private function parseDate(?string $value): ?string
    {
        if (! $value || $value === '0000-00-00 00:00:00') {
            return null;
        }

        return $value;
    }

    private function nullIfEmpty(?string $value): ?string
    {
        $v = trim((string) $value);
        return $v === '' ? null : $v;
    }

    private function showSummary(): void
    {
        $this->info('=== RESUMEN ===');
        $this->table(['Recurso', 'Creados', 'Actualizados', 'Otros'], [
            ['Categorías', $this->stats['categories_created'], $this->stats['categories_updated'], $this->stats['categories_skipped'].' saltadas'],
            ['Usuarios',   $this->stats['users_created'],      $this->stats['users_updated'],      '—'],
            ['Artículos',  $this->stats['articles_created'],   $this->stats['articles_updated'],   $this->stats['articles_failed'].' fallidos'],
        ]);

        if ($this->dryRun) {
            $this->warn('Recordá: estuvo en DRY-RUN, no se escribió nada.');
        }
    }
}
