<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Traduce texto usando la API de OpenAI.
 * Configuración en .env: OPENAI_API_KEY y OPENAI_MODEL.
 */
class TranslationService
{
    /**
     * Traduce un texto de un idioma a otro.
     */
    public static function translate(string $text, string $fromLang, string $toLang): ?string
    {
        if (empty(trim($text))) {
            return '';
        }

        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.model', 'gpt-4.1-nano');

        if (!$apiKey) {
            throw new \RuntimeException('OPENAI_API_KEY no configurada en .env');
        }

        $langNames = [
            'es' => 'Spanish (Argentine)',
            'en' => 'English',
            'pt' => 'Brazilian Portuguese',
            'fr' => 'French',
            'it' => 'Italian',
        ];

        $fromName = $langNames[$fromLang] ?? $fromLang;
        $toName = $langNames[$toLang] ?? $toLang;

        $isHtml = strip_tags($text) !== $text;

        $systemPrompt = $isHtml
            ? "You are a professional translator. Translate the following HTML content from {$fromName} to {$toName}. Preserve ALL HTML tags, attributes, and structure exactly. Only translate the text content between tags. Do not add explanations."
            : "You are a professional translator. Translate the following text from {$fromName} to {$toName}. Maintain the same tone and style. Do not add explanations, just return the translation.";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $text],
            ],
            'temperature' => 0.3,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('OpenAI API error: ' . $response->body());
        }

        return $response->json('choices.0.message.content');
    }

    /**
     * Traduce múltiples campos de un modelo a un idioma.
     * Devuelve un array [campo => traducción].
     */
    public static function translateFields(array $fields, string $fromLang, string $toLang): array
    {
        $translations = [];

        foreach ($fields as $field => $text) {
            if (empty(trim($text ?? ''))) {
                $translations[$field] = '';
                continue;
            }

            $translations[$field] = self::translate($text, $fromLang, $toLang);
        }

        return $translations;
    }
}
