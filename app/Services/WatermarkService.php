<?php

namespace App\Services;

/**
 * Aplica marca de agua (gaviota) a imágenes.
 * Se usa automáticamente al subir imágenes desde Filament.
 */
class WatermarkService
{
    private static int $sizePct = 15;
    private static int $opacity = 40;

    /**
     * Aplica watermark a una imagen en el storage público.
     * @param string $relativePath Ruta relativa dentro de storage/app/public/
     */
    public static function apply(string $relativePath): bool
    {
        $watermarkPath = public_path('images/logo-icon.png');
        $imagePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($watermarkPath) || !file_exists($imagePath)) {
            return false;
        }

        $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            return false;
        }

        try {
            $watermark = imagecreatefrompng($watermarkPath);
            if (!$watermark) return false;

            imagealphablending($watermark, true);
            imagesavealpha($watermark, true);
            $wmWidth = imagesx($watermark);
            $wmHeight = imagesy($watermark);

            $image = match ($ext) {
                'jpg', 'jpeg' => imagecreatefromjpeg($imagePath),
                'png' => imagecreatefrompng($imagePath),
                'webp' => imagecreatefromwebp($imagePath),
            };

            if (!$image) {
                imagedestroy($watermark);
                return false;
            }

            $imgWidth = imagesx($image);
            $imgHeight = imagesy($image);

            // Tamaño proporcional
            $targetWmWidth = (int) ($imgWidth * self::$sizePct / 100);
            $targetWmHeight = (int) ($wmHeight * ($targetWmWidth / $wmWidth));

            // Redimensionar watermark
            $resizedWm = imagecreatetruecolor($targetWmWidth, $targetWmHeight);
            imagealphablending($resizedWm, false);
            imagesavealpha($resizedWm, true);
            $transparent = imagecolorallocatealpha($resizedWm, 0, 0, 0, 127);
            imagefill($resizedWm, 0, 0, $transparent);
            imagecopyresampled($resizedWm, $watermark, 0, 0, 0, 0, $targetWmWidth, $targetWmHeight, $wmWidth, $wmHeight);

            // Posición: centro
            $destX = (int) (($imgWidth - $targetWmWidth) / 2);
            $destY = (int) (($imgHeight - $targetWmHeight) / 2);

            // Aplicar con opacidad
            imagealphablending($image, true);
            $temp = imagecreatetruecolor($targetWmWidth, $targetWmHeight);
            imagecopy($temp, $image, 0, 0, $destX, $destY, $targetWmWidth, $targetWmHeight);
            imagealphablending($temp, true);
            imagecopy($temp, $resizedWm, 0, 0, 0, 0, $targetWmWidth, $targetWmHeight);
            imagecopymerge($image, $temp, $destX, $destY, 0, 0, $targetWmWidth, $targetWmHeight, self::$opacity);

            // Guardar
            match ($ext) {
                'jpg', 'jpeg' => imagejpeg($image, $imagePath, 90),
                'png' => imagepng($image, $imagePath, 8),
                'webp' => imagewebp($image, $imagePath, 85),
            };

            imagedestroy($image);
            imagedestroy($watermark);
            imagedestroy($resizedWm);
            imagedestroy($temp);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
