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
                            {--dir=news-images : Directorio dentro de storage/app/public/ a procesar}';

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

        $dir = $this->option('dir');
        $force = (bool) $this->option('force');
        $sizePct = (int) $this->option('size');
        $opacity = (int) $this->option('opacity');

        $storagePath = storage_path('app/public/' . $dir);
        if (!is_dir($storagePath)) {
            $this->error("Directorio no encontrado: {$storagePath}");
            return self::FAILURE;
        }

        // Archivo de tracking para saber cuáles ya procesamos
        $trackingFile = $storagePath . '/.watermarked';
        $alreadyDone = [];
        if (!$force && file_exists($trackingFile)) {
            $alreadyDone = array_filter(explode("\n", file_get_contents($trackingFile)));
        }

        // Cargar watermark una sola vez
        $watermark = imagecreatefrompng($watermarkPath);
        if (!$watermark) {
            $this->error('No se pudo cargar el PNG del watermark.');
            return self::FAILURE;
        }
        imagealphablending($watermark, true);
        imagesavealpha($watermark, true);
        $wmWidth = imagesx($watermark);
        $wmHeight = imagesy($watermark);

        // Buscar todas las imágenes
        $files = glob($storagePath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $total = count($files);

        $this->info("Procesando {$total} imágenes en {$dir}/ (watermark {$sizePct}%, opacidad {$opacity}%)...");
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
        $this->newLine(2);

        // Actualizar tracking
        if (!empty($newlyDone)) {
            file_put_contents($trackingFile, implode("\n", array_merge($alreadyDone, $newlyDone)) . "\n");
        }

        imagedestroy($watermark);

        $this->table(['Métrica', 'Valor'], [
            ['Procesadas', $this->processed],
            ['Ya tenían watermark', $this->skipped],
            ['Fallidas', $this->failed],
        ]);

        return self::SUCCESS;
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

        // Aplicar opacidad al watermark
        if ($opacity < 100) {
            $this->setImageOpacity($resizedWm, $opacity / 100);
        }

        // Posición: esquina inferior derecha con margen del 3%
        $margin = (int) ($imgWidth * 0.03);
        $destX = $imgWidth - $targetWmWidth - $margin;
        $destY = $imgHeight - $targetWmHeight - $margin;

        // Merge watermark sobre la imagen
        imagealphablending($image, true);
        imagecopy($image, $resizedWm, $destX, $destY, 0, 0, $targetWmWidth, $targetWmHeight);

        // Guardar (sobreescribe)
        match ($ext) {
            'jpg', 'jpeg' => imagejpeg($image, $filePath, 90),
            'png' => imagepng($image, $filePath, 8),
            'webp' => imagewebp($image, $filePath, 85),
        };

        imagedestroy($image);
        imagedestroy($resizedWm);
    }

    /**
     * Reduce la opacidad de una imagen con canal alpha.
     */
    private function setImageOpacity($image, float $opacity): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                // Aumentar la transparencia según la opacidad deseada
                $newAlpha = (int) (127 - ((127 - $alpha) * $opacity));
                $rgb = $color & 0x00FFFFFF;
                $newColor = imagecolorallocatealpha(
                    $image,
                    ($rgb >> 16) & 0xFF,
                    ($rgb >> 8) & 0xFF,
                    $rgb & 0xFF,
                    $newAlpha
                );
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
    }
}
