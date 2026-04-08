<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Descarga las imágenes destacadas que apuntan al Joomla vivo
 * (cooperativaliberte.coop) y las guarda localmente, reescribiendo
 * el campo featured_image de cada artículo para que apunte a la copia.
 *
 * Idempotente: re-correrlo no descarga lo que ya está bajado.
 * Si una descarga falla, el registro queda como estaba para reintentar.
 */
class DownloadJoomlaImages extends Command
{
    protected $signature = 'joomla:download-images
                            {--force : Re-descarga incluso las que ya están bajadas localmente}
                            {--limit=0 : Procesa solo los primeros N registros (0 = todos)}
                            {--timeout=30 : Timeout HTTP por descarga, en segundos}';

    protected $description = 'Descarga las imágenes destacadas del Joomla viejo a storage/app/public/news-images/';

    /**
     * Carpeta de destino dentro del disco "public".
     */
    private const TARGET_DIR = 'news-images';

    /**
     * Prefijo de URLs que consideramos "todavía apuntando al Joomla vivo"
     * y por lo tanto candidatas a descarga.
     */
    private const REMOTE_URL_PREFIX = 'https://cooperativaliberte.coop/';

    private array $stats = [
        'candidates'  => 0,
        'downloaded'  => 0,
        'skipped'     => 0,
        'cached'      => 0,
        'failed'      => 0,
    ];

    public function handle(): int
    {
        $force   = (bool) $this->option('force');
        $limit   = (int) $this->option('limit');
        $timeout = (int) $this->option('timeout');

        // Verificamos que el storage:link esté hecho. Sin esto, los archivos
        // se guardan bien pero no son accesibles vía /storage/...
        if (! file_exists(public_path('storage'))) {
            $this->warn('public/storage no existe. Corré primero: php artisan storage:link');
            if (! $this->confirm('¿Continuar igual? Los archivos se guardarán pero no serán servibles hasta que linkees.', false)) {
                return self::FAILURE;
            }
        }

        $query = News::query()
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '');

        if (! $force) {
            $query->where('featured_image', 'LIKE', self::REMOTE_URL_PREFIX.'%');
        }

        // count() ignora cualquier limit() en el query builder, así que esto
        // siempre devuelve el total real de candidatos.
        $matching = $query->count();
        $total = $limit > 0 ? min($limit, $matching) : $matching;
        $this->stats['candidates'] = $total;

        if ($total === 0) {
            $this->info('No hay imágenes pendientes de descarga.');
            return self::SUCCESS;
        }

        $this->info("Procesando {$total} imágenes (de {$matching} candidatos en total)...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Con --limit usamos cursor() en una query simple. Sin --limit usamos
        // chunkById para mantener el uso de memoria acotado en el caso real
        // de cientos/miles de registros.
        if ($limit > 0) {
            foreach ($query->orderBy('id')->take($limit)->cursor() as $news) {
                $this->processOne($news, $timeout);
                $bar->advance();
            }
        } else {
            $query->orderBy('id')->chunkById(50, function ($newsBatch) use ($bar, $timeout) {
                foreach ($newsBatch as $news) {
                    $this->processOne($news, $timeout);
                    $bar->advance();
                }
            });
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(['Métrica', 'Valor'], [
            ['Candidatos',          $this->stats['candidates']],
            ['Descargados nuevos',  $this->stats['downloaded']],
            ['Ya estaban en disco', $this->stats['cached']],
            ['Saltados',            $this->stats['skipped']],
            ['Fallidos',            $this->stats['failed']],
        ]);

        if ($this->stats['failed'] > 0) {
            $this->warn('Algunas descargas fallaron. Re-correr el comando reintenta solo las pendientes.');
        }

        return self::SUCCESS;
    }

    private function processOne(News $news, int $timeout): void
    {
        $url = $news->featured_image;

        // Si --force está activo y el campo ya es ruta local, no podemos
        // re-descargar (no tenemos la URL original). Lo saltamos.
        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $this->stats['skipped']++;
            return;
        }

        $localRelativePath = $this->buildLocalPath($url);
        if (! $localRelativePath) {
            $this->stats['skipped']++;
            return;
        }

        // Si el archivo ya existe en disco, solo actualizamos el campo y listo.
        if (Storage::disk('public')->exists($localRelativePath)) {
            $news->update(['featured_image' => $localRelativePath]);
            $this->stats['cached']++;
            return;
        }

        // Descarga real
        try {
            $response = Http::timeout($timeout)
                ->withOptions(['verify' => false]) // Ferozo a veces tiene certs raros
                ->get($url);

            if (! $response->successful()) {
                $this->stats['failed']++;
                $this->newLine();
                $this->error(sprintf(
                    'HTTP %d para joomla_id=%d: %s',
                    $response->status(),
                    $news->joomla_id,
                    Str::limit($url, 80)
                ));
                return;
            }

            Storage::disk('public')->put($localRelativePath, $response->body());
            $news->update(['featured_image' => $localRelativePath]);
            $this->stats['downloaded']++;
        } catch (\Throwable $e) {
            $this->stats['failed']++;
            $this->newLine();
            $this->error(sprintf(
                'Excepción joomla_id=%d: %s',
                $news->joomla_id,
                $e->getMessage()
            ));
        }
    }

    /**
     * Construye la ruta local relativa para una URL remota.
     *
     * Patrón: news-images/{hash8}-{slug}.{ext}
     *   - hash8: primeros 8 caracteres de md5(URL completa) → idempotente,
     *     misma URL siempre genera la misma ruta, evita colisiones.
     *   - slug: versión slugificada del nombre original (legible).
     *   - ext: extensión original.
     */
    private function buildLocalPath(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! $path) {
            return null;
        }

        $basename = basename(rawurldecode($path));
        if ($basename === '' || $basename === '/') {
            return null;
        }

        $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg');
        $name = pathinfo($basename, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'image';
        $hash = substr(md5($url), 0, 8);

        return sprintf('%s/%s-%s.%s', self::TARGET_DIR, $hash, $slug, $ext);
    }
}
