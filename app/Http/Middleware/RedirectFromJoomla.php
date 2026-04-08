<?php

namespace App\Http\Middleware;

use App\Models\News;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Detecta URLs heredadas del Joomla viejo y emite 301 al equivalente Laravel.
 *
 * El Joomla viejo usaba este patrón de URL para los artículos:
 *   https://cooperativaliberte.coop/es/noticias/890-tai-chi-la-disciplina-...
 *
 * Donde:
 *   - "es" es el prefijo de idioma (puede o no estar presente)
 *   - "noticias" es el alias de la categoría (puede haber subcategorías anidadas)
 *   - "890" es el ID numérico del artículo (clave estable)
 *   - el resto es el slug
 *
 * El ID es la única parte estable de la URL: el slug pudo haber tenido errores
 * o cambios en su momento. Por eso buscamos por ID y no por slug.
 *
 * Si encontramos el artículo en la base local (vía news.joomla_id), redirigimos
 * 301 a su nueva URL canónica. Si no, dejamos seguir el request normalmente
 * para que el router de Laravel resuelva (o devuelva 404).
 */
class RedirectFromJoomla
{
    /**
     * Patrón: cualquier path que termine en "/{digitos}-{cualquier-cosa}".
     * Captura el ID en el grupo 1.
     */
    private const JOOMLA_URL_PATTERN = '#/(\d+)-[^/]+/?$#';

    public function handle(Request $request, Closure $next): Response
    {
        // Solo procesamos GET — no tiene sentido redirigir POSTs.
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $path = $request->path();

        // Si el path no contiene el patrón {id}-{slug}, no hay nada que redirigir.
        if (! preg_match(self::JOOMLA_URL_PATTERN, '/'.$path, $matches)) {
            return $next($request);
        }

        $joomlaId = (int) $matches[1];

        $news = News::where('joomla_id', $joomlaId)->first();

        if (! $news) {
            // El ID no corresponde a ningún artículo migrado. Dejamos seguir
            // por si Laravel sabe qué hacer con esta URL (probable 404).
            return $next($request);
        }

        // Construimos la nueva URL canónica y emitimos 301.
        return redirect()->route('news.show', ['slug' => $news->slug], 301);
    }
}
