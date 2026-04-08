<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestJoomlaConnection extends Command
{
    protected $signature = 'joomla:test-connection';

    protected $description = 'Verifica que la conexión a la base coop_joomla_legacy funciona y muestra estadísticas básicas.';

    public function handle(): int
    {
        $this->info('Probando conexión a joomla_legacy...');

        try {
            $conn = DB::connection('joomla_legacy');
            $conn->getPdo();
        } catch (\Throwable $e) {
            $this->error('No se pudo conectar: '.$e->getMessage());
            return self::FAILURE;
        }

        $this->info('Conexión OK.');
        $this->newLine();

        // El prefijo js4ox_ se aplica automáticamente, así que las queries
        // usan el nombre "lógico" sin prefijo (content, categories, users).
        $totalArticulos = $conn->table('content')->count();
        $articulosEsPub = $conn->table('content')
            ->where('language', 'es-ES')
            ->where('state', 1)
            ->count();
        $totalCategorias = $conn->table('categories')
            ->where('extension', 'com_content')
            ->where('language', 'es-ES')
            ->count();
        $totalUsuarios = $conn->table('users')->count();

        $this->table(['Métrica', 'Valor'], [
            ['Total artículos (todos los idiomas)', $totalArticulos],
            ['Artículos publicados en es-ES', $articulosEsPub],
            ['Categorías com_content en es-ES', $totalCategorias],
            ['Total usuarios', $totalUsuarios],
        ]);

        return self::SUCCESS;
    }
}
