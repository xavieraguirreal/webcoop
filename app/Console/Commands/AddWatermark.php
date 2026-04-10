<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Agrega la marca de agua (gaviota) a todas las imágenes del sitio.
 * El tamaño del watermark es proporcional a cada imagen (15% del ancho).
 * Posición: esquina inferior derecha con margen.
 * Idempotente: no reprocesa imágenes que ya tienen watermark (usa un archivo de tracking).
 */
class AddWatermark extends Command
{
    protected $signature = 'images:watermark
                            {--force : Re-procesar incluso las que ya tienen marca de agua}
                            {--size=15 : Porcentaje del ancho de la imagen que ocupa el watermark}
                            {--opacity=30 : Opacidad del watermark (0-100)}
                            {--dir=news-images : Directorio dentro de storage/app/public/ a procesar}
                            {--all : Procesar TODOS los directorios de imágenes (news-images + hero)}';

    protected $description = 'Agrega marca de agua (gaviota) a las imágenes del storage.';

    private int $processed = 0;
    private int $skipped = 0;
    private int $failed = 0;

    public function handle(): int
    {
        $watermarkPath = public_path('images/logo-icon.png');
        if (!file_exists($watermarkPath)) {
            $this->error('No se encontró el logo en public/images/logo-icon.png');
            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $sizePct = (int) $this->option('size');
        $opacity = (int) $this->option('opacity');
        $all = (bool) $this->option('all');

        // Directorios a procesar
        $dirs = [];
        if ($all) {
            $dirs[] = storage_path('app/public/news-images');
            $dirs[] = public_path('images/hero');
        } else {
            $dirs[] = storage_path('app/public/' . $this->option('dir'));
        }

        foreach ($dirs as $storagePath) {
            if (!is_dir($storagePath)) {
                $this->warn("Directorio no encontrado: {$storagePath} — saltando.");
                continue;
            }
            $this->info("\nProcesando: {$storagePath}");
            $this->processDirectory($storagePath, $watermarkPath, $force, $sizePct, $opacity);
        }

        $this->newLine();
        $this->table(['Métrica', 'Valor'], [
            ['Procesadas', $this->processed],
            ['Ya tenían watermark', $this->skipped],
            ['Fallidas', $this->failed],
        ]);

        return self::SUCCESS;
    }

    private function processDirectory(string $storagePath, string $watermarkPath, bool $force, int $sizePct, int $opacity): void
    {
        $trackingFile = $storagePath . '/.watermarked';
        $alreadyDone = [];
        if (!$force && file_exists($trackingFile)) {
            $alreadyDone = array_filter(explode("\n", file_get_contents($trackingFile)));
        }

        $watermark = imagecreatefrompng($watermarkPath);
        if (!$watermark) {
            $this->error('No se pudo cargar el PNG del watermark.');
            return;
        }
        imagealphablending($watermark, true);
        imagesavealpha($watermark, true);
        $wmWidth = imagesx($watermark);
        $wmHeight = imagesy($watermark);

        $files = glob($storagePath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $total = count($files);

        $this->info("{$total} imágenes (watermark {$sizePct}%, opacidad {$opacity}%)");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $newlyDone = [];

        foreach ($files as $file) {
            $basename = basename($file);

            if (!$force && in_array($basename, $alreadyDone)) {
                $this->skipped++;
                $bar->advance();
                continue;
            }

            try {
                $this->applyWatermark($file, $watermark, $wmWidth, $wmHeight, $sizePct, $opacity);
                $newlyDone[] = $basename;
                $this->processed++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->newLine();
                $this->error("Falló {$basename}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if (!empty($newlyDone)) {
            file_put_contents($trackingFile, implode("\n", array_merge($alreadyDone, $newlyDone)) . "\n");
        }

        imagedestroy($watermark);
    }

    private function applyWatermark(string $filePath, $watermark, int $wmWidth, int $wmHeight, int $sizePct, int $opacity): void
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Cargar imagen según formato
        $image = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($filePath),
            'png' => imagecreatefrompng($filePath),
            'webp' => imagecreatefromwebp($filePath),
            default => throw new \RuntimeException("Formato no soportado: {$ext}"),
        };

        if (!$image) {
            throw new \RuntimeException('No se pudo cargar la imagen.');
        }

        $imgWidth = imagesx($image);
        $imgHeight = imagesy($image);

        // Calcular tamaño proporcional del watermark
        $targetWmWidth = (int) ($imgWidth * $sizePct / 100);
        $targetWmHeight = (int) ($wmHeight * ($targetWmWidth / $wmWidth));

        // Crear watermark redimensionado con transparencia
        $resizedWm = imagecreatetruecolor($targetWmWidth, $targetWmHeight);
        imagealphablending($resizedWm, false);
        imagesavealpha($resizedWm, true);
        $transparent = imagecolorallocatealpha($resizedWm, 0, 0, 0, 127);
        imagefill($resizedWm, 0, 0, $transparent);
        imagecopyresampled($resizedWm, $watermark, 0, 0, 0, 0, $targetWmWidth, $targetWmHeight, $wmWidth, $wmHeight);

        // Posición: esquina inferior derecha con margen del 3%
        $margin = (int) ($imgWidth * 0.03);
        $destX = $imgWidth - $targetWmWidth - $margin;
        $destY = $imgHeight - $targetWmHeight - $margin;

        // Merge watermark sobre la imagen con opacidad
        imagealphablending($image, true);

        if ($opacity >= 100) {
            // Opacidad completa: copiar directo con alpha
            imagecopy($image, $resizedWm, $destX, $destY, 0, 0, $targetWmWidth, $targetWmHeight);
        } else {
            // Opacidad parcial: usar una capa intermedia
            $temp = imagecreatetruecolor($targetWmWidth, $targetWmHeight);
            // Copiar la porción de la imagen destino
            imagecopy($temp, $image, 0, 0, $destX, $destY, $targetWmWidth, $targetWmHeight);
            // Sobreponer el watermark con alpha
            imagealphablending($temp, true);
            imagecopy($temp, $resizedWm, 0, 0, 0, 0, $targetWmWidth, $targetWmHeight);
            // Mergear la capa de vuelta con opacidad
            imagecopymerge($image, $temp, $destX, $destY, 0, 0, $targetWmWidth, $targetWmHeight, $opacity);
            imagedestroy($temp);
        }

        // Guardar (sobreescribe)
        match ($ext) {
            'jpg', 'jpeg' => imagejpeg($image, $filePath, 90),
            'png' => imagepng($image, $filePath, 8),
            'webp' => imagewebp($image, $filePath, 85),
        };

        imagedestroy($image);
        imagedestroy($resizedWm);
    }

}
