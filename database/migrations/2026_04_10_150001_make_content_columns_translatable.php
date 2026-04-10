<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Convierte las columnas de contenido de string/text a JSON para
 * soportar traducciones con Spatie Translatable.
 *
 * Los datos existentes (en español) se envuelven automáticamente
 * como {"es": "contenido actual"} para no perder nada.
 */
return new class extends Migration
{
    public function up(): void
    {
        // === NEWS ===
        $this->convertToJson('news', ['title', 'excerpt', 'body', 'meta_title', 'meta_description']);

        // === PAGES ===
        $this->convertToJson('pages', ['title', 'body', 'meta_title', 'meta_description']);

        // === WORK AREAS ===
        $this->convertToJson('work_areas', ['name', 'short_description', 'description']);

        // === COURSES ===
        $this->convertToJson('courses', ['name', 'description']);

        // === CATEGORIES ===
        $this->convertToJson('categories', ['name', 'description']);
    }

    public function down(): void
    {
        // Revertir: extraer solo el valor 'es' del JSON
        $this->revertFromJson('news', ['title', 'excerpt', 'body', 'meta_title', 'meta_description']);
        $this->revertFromJson('pages', ['title', 'body', 'meta_title', 'meta_description']);
        $this->revertFromJson('work_areas', ['name', 'short_description', 'description']);
        $this->revertFromJson('courses', ['name', 'description']);
        $this->revertFromJson('categories', ['name', 'description']);
    }

    /**
     * Convierte columnas de texto plano a JSON envolviendo el contenido
     * existente como {"es": "contenido"}.
     */
    private function convertToJson(string $table, array $columns): void
    {
        // Primero: envolver datos existentes en JSON
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $updates = [];
            foreach ($columns as $col) {
                $value = $row->$col;
                if ($value !== null && !$this->isJson($value)) {
                    $updates[$col] = json_encode(['es' => $value], JSON_UNESCAPED_UNICODE);
                }
            }
            if (!empty($updates)) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }

        // Después: cambiar el tipo de columna a JSON
        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                $table->json($col)->nullable()->change();
            }
        });
    }

    private function revertFromJson(string $table, array $columns): void
    {
        // Extraer valor 'es' del JSON
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $updates = [];
            foreach ($columns as $col) {
                $value = $row->$col;
                if ($value !== null && $this->isJson($value)) {
                    $decoded = json_decode($value, true);
                    $updates[$col] = $decoded['es'] ?? $value;
                }
            }
            if (!empty($updates)) {
                DB::table($table)->where('id', $row->id)->update($updates);
            }
        }

        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                $table->longText($col)->nullable()->change();
            }
        });
    }

    private function isJson(string $value): bool
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE && str_starts_with(trim($value), '{');
    }
};
