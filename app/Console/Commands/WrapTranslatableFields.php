<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WrapTranslatableFields extends Command
{
    protected $signature = 'db:wrap-translatable';
    protected $description = 'Wrap plain-text fields into Spatie Translatable JSON format {"es": "value"}';

    public function handle(): void
    {
        $tables = [
            'news' => ['title', 'excerpt', 'body', 'meta_title', 'meta_description'],
            'work_areas' => ['name', 'short_description', 'description'],
            'pages' => ['title', 'body', 'meta_title', 'meta_description'],
            'categories' => ['name'],
        ];

        foreach ($tables as $table => $columns) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Table {$table} not found, skipping.");
                continue;
            }

            $rows = DB::table($table)->get();
            $count = 0;

            foreach ($rows as $row) {
                $updates = [];
                foreach ($columns as $col) {
                    $value = $row->$col ?? null;
                    if ($value === null) continue;

                    json_decode($value);
                    if (json_last_error() === JSON_ERROR_NONE && str_starts_with(trim($value), '{')) {
                        continue;
                    }

                    $updates[$col] = json_encode(['es' => $value], JSON_UNESCAPED_UNICODE);
                }

                if (!empty($updates)) {
                    DB::table($table)->where('id', $row->id)->update($updates);
                    $count++;
                }
            }

            $this->info("{$table}: {$count} rows wrapped.");
        }

        $this->info('Done.');
    }
}
