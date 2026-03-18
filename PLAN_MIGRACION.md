# Plan de Migración - cooperativaLIBERTÉ

## De Joomla a Laravel + Filament

---

## 1. Estrategia de migración (sin downtime)

```
FASE 1 - Desarrollo (sitio Joomla sigue activo)
───────────────────────────────────────────────
cooperativaliberte.coop          → Joomla (público, sin tocar)
dev.cooperativaliberte.coop      → Laravel nuevo (desarrollo)

FASE 2 - Lanzamiento
───────────────────────────────────────────────
cooperativaliberte.coop          → Laravel nuevo (público)
Joomla                           → se elimina

Redirecciones 301 para todas las URLs viejas → preserva SEO
```

### Pasos para el lanzamiento:
1. Crear subdominio `dev.cooperativaliberte.coop` en panel Ferozo
2. Crear base de datos MySQL nueva para el proyecto Laravel
3. Instalar Laravel + Filament en el subdominio vía SSH
4. Desarrollar y cargar contenido en el subdominio
5. Revisar y aprobar el sitio nuevo
6. Apuntar dominio principal al nuevo sitio
7. Activar redirecciones 301 de URLs viejas
8. Eliminar Joomla

---

## 2. Stack tecnológico

| Componente | Tecnología | Motivo |
|---|---|---|
| Backend / Framework | Laravel 11 (PHP 8.3) | Compatible con Ferozo, moderno, robusto |
| Panel Admin | Filament 3 | Admin completo, gratis, hermoso |
| Base de datos | MySQL | Disponible en DonWeb |
| Frontend | Blade + Tailwind CSS + Alpine.js | Rápido, moderno, sin dependencia de Node en producción |
| Idiomas | Laravel Localization + Filament Translatable | Multi-idioma nativo |
| SEO | Metaetiquetas dinámicas, sitemap XML, URLs amigables | Preservar y mejorar posicionamiento |
| Imágenes | Spatie Media Library | Gestión de imágenes optimizada |
| Instagram | Instagram Graph API | Publicación automática |

---

## 3. Estructura del sitio público

### Navegación principal:
```
INICIO    NOSOTROS    ÁREAS DE TRABAJO    FORMACIÓN    NOTICIAS    RELATOS    CONTACTO
```

### Mapa de páginas:

```
/ (Inicio)
├── /nosotros
│   ├── /nosotros/quienes-somos
│   ├── /nosotros/historia
│   └── /nosotros/fondo-de-ayuda-a-victimas
├── /areas-de-trabajo
│   ├── /areas/marroquineria
│   ├── /areas/carpinteria
│   ├── /areas/herreria
│   ├── /areas/huerta-organica
│   ├── /areas/apicultura
│   ├── /areas/artesanias
│   └── /areas/punto-de-paz (restaurante)
├── /formacion
│   ├── /formacion/oferta-educativa
│   ├── /formacion/certificados
│   └── /formacion/matriculacion
├── /noticias
│   ├── /noticias/{slug} (artículos individuales)
│   ├── /noticias/categoria/{categoria}
│   └── /noticias/notinfierno
├── /relatos
│   └── /relatos/{slug}
├── /radio (Radio Aires de Liberté)
├── /contacto
└── /es /pt /it /fr /en (multi-idioma)
```

---

## 4. Diseño del sitio público - Propuesta visual

### Concepto: "Dignidad a través del trabajo"

El diseño transmite **seriedad, profesionalismo y humanidad**. No es un sitio
"carcelario" ni asistencialista. Es el sitio de una **cooperativa de trabajo real**
que produce, forma y transforma.

### Paleta de colores:

```
Primario:     #1B2A4A  (azul oscuro profundo - confianza, seriedad)
Secundario:   #C8A96E  (dorado sobrio - dignidad, valor del trabajo)
Acento:       #2D6A4F  (verde oscuro - crecimiento, esperanza)
Neutros:      #F5F3EF  (crema claro - calidez)
              #333333  (gris oscuro - textos)
              #FFFFFF  (blanco - espacios)
```

### Tipografía:
```
Títulos:   "DM Serif Display" o "Playfair Display" (serif, elegante, seria)
Cuerpo:    "Inter" o "Source Sans Pro" (sans-serif, legible, moderna)
```

### Página de INICIO - Estructura:

```
┌─────────────────────────────────────────────────────────┐
│  LOGO cooperativaLIBERTÉ          NAV          🌐 ES ▼ │
├─────────────────────────────────────────────────────────┤
│                                                         │
│           HERO - Imagen de fondo gran formato           │
│        (manos trabajando, taller, producto final)       │
│                                                         │
│         "Transformamos realidades                       │
│          a través del trabajo"                          │
│                                                         │
│         [CONOCER LA COOPERATIVA]  [NUESTRO TRABAJO]     │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│              CIFRAS DE IMPACTO                          │
│   ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐              │
│   │ 200+ │  │ 2014 │  │  7+  │  │ 100+ │              │
│   │miemb.│  │desde │  │áreas │  │cursos│              │
│   └──────┘  └──────┘  └──────┘  └──────┘              │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│              ÁREAS DE TRABAJO                           │
│   ┌─────────┐ ┌─────────┐ ┌─────────┐                 │
│   │  foto   │ │  foto   │ │  foto   │                 │
│   │Marroqu. │ │Carpint. │ │Herrería │                 │
│   │ breve   │ │ breve   │ │ breve   │                 │
│   │ desc.   │ │ desc.   │ │ desc.   │                 │
│   └─────────┘ └─────────┘ └─────────┘                 │
│   ┌─────────┐ ┌─────────┐ ┌─────────┐                 │
│   │  foto   │ │  foto   │ │  foto   │                 │
│   │ Huerta  │ │Apicult. │ │Pto. Paz │                 │
│   └─────────┘ └─────────┘ └─────────┘                 │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│              ÚLTIMAS NOTICIAS                           │
│   ┌──────────────┐ ┌──────────────┐ ┌──────────────┐  │
│   │   imagen     │ │   imagen     │ │   imagen     │  │
│   │   título     │ │   título     │ │   título     │  │
│   │   fecha      │ │   fecha      │ │   fecha      │  │
│   │   extracto   │ │   extracto   │ │   extracto   │  │
│   └──────────────┘ └──────────────┘ └──────────────┘  │
│                                                         │
│                    [VER TODAS →]                         │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│              ALIANZAS Y RECONOCIMIENTOS                 │
│   logos: Procuración Penitenciaria | UNMdP | INTA |    │
│          Fed. Arg. Cooperativas | Pensamiento Penal    │
│                                                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│   FOOTER                                                │
│   Logo | Contacto | Redes | Radio | Legal | Idiomas    │
│   "Cooperativa de Trabajo Liberté Ltda."                │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### Página de NOTICIAS:

```
┌─────────────────────────────────────────────────────────┐
│  Filtros: [Todas] [notINFIERNO] [En los medios] [2025] │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │  NOTICIA DESTACADA - imagen grande               │   │
│  │  Título / Fecha / Extracto                       │   │
│  └──────────────────────────────────────────────────┘   │
│                                                         │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │  miniatura  │ │  miniatura  │ │  miniatura  │      │
│  │  título     │ │  título     │ │  título     │      │
│  │  fecha      │ │  fecha      │ │  fecha      │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
│                                                         │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐      │
│  │  ...        │ │  ...        │ │  ...        │      │
│  └─────────────┘ └─────────────┘ └─────────────┘      │
│                                                         │
│              [CARGAR MÁS NOTICIAS]                      │
└─────────────────────────────────────────────────────────┘
```

### Principios de diseño:
- **Espacios amplios** - el sitio respira, no está sobrecargado
- **Fotografías grandes y reales** - el trabajo real es el protagonista
- **Sin stock photos** - solo fotos reales de la cooperativa
- **Tipografía grande y clara** - legibilidad ante todo
- **Responsive** - perfecto en celular, tablet y desktop
- **Velocidad** - carga en menos de 3 segundos
- **Accesibilidad** - contraste adecuado, textos legibles

---

## 5. Panel de administración (Filament)

### Módulos del admin:

```
/admin
├── Dashboard (estadísticas: visitas, artículos publicados, etc.)
├── Noticias
│   ├── Crear / Editar / Eliminar
│   ├── Categorías (General, notINFIERNO, En los medios)
│   ├── Editor de texto enriquecido (negritas, imágenes, videos)
│   ├── Imagen destacada
│   ├── Programar publicación (fecha futura)
│   ├── Estado: borrador / publicado
│   ├── SEO: meta título, meta descripción, slug personalizable
│   └── Toggle: "Publicar también en Instagram"
├── Relatos
│   ├── Crear / Editar / Eliminar
│   ├── Tags (Taller Literario, Mentes en Jaque, etc.)
│   └── Autor
├── Páginas estáticas
│   ├── Quiénes somos
│   ├── Historia
│   ├── FAV
│   └── Cualquier página nueva
├── Áreas de trabajo
│   ├── Nombre, descripción, fotos
│   └── Orden de aparición
├── Instituto de Formación
│   ├── Cursos / Certificados
│   └── Matriculación (formulario)
├── Medios (galería de imágenes)
├── Menús de navegación
├── Traducciones (multi-idioma)
├── Usuarios y roles
│   ├── Administrador (acceso total)
│   ├── Redactor (crear/editar noticias y relatos)
│   └── Colaborador (solo crear borradores)
└── Configuración del sitio
    ├── Logo, favicon
    ├── Redes sociales
    ├── Datos de contacto
    └── Instagram API config
```

---

## 6. Integración con Instagram (publicación automática)

### Cómo funciona:

```
Redactor crea noticia → Activa toggle "Publicar en Instagram"
    → Al guardar/publicar:
        1. Se toma la imagen destacada de la noticia
        2. Se genera un texto: título + extracto + link al artículo
        3. Se publica automáticamente en Instagram vía API
        4. Se guarda el ID del post de Instagram en la BD
```

### Requisitos previos:
1. **Cuenta de Instagram Business** (o Creator) - no funciona con cuenta personal
2. **Página de Facebook** vinculada a la cuenta de Instagram
3. **App de Meta (Facebook Developer)** - se crea gratis
4. **Token de acceso** de larga duración

### Qué se puede publicar automáticamente:
- **Imagen + caption** (foto de la noticia + texto)
- **Carousel** (varias imágenes)
- **NO se pueden publicar Stories** vía API (limitación de Meta)

### Flujo técnico:
```
Laravel → Instagram Graph API (v18.0+)
    POST /{ig-user-id}/media
        image_url: URL de la imagen destacada
        caption: "📰 {título}\n\n{extracto}\n\n🔗 Leé la nota completa en cooperativaliberte.coop/noticias/{slug}"
    POST /{ig-user-id}/media_publish
        creation_id: {id del paso anterior}
```

### Alternativa sin API directa:
Si la cuenta no es Business, se puede usar un webhook que envíe
la noticia a **Make.com** (ex Integromat) o **n8n** (self-hosted) que
luego publique en Instagram. Esto agrega un intermediario pero es más
flexible (puede publicar también en Facebook, Twitter/X, etc.).

---

## 7. Preservación de URLs (SEO)

### URLs actuales de Joomla a mantener:

```
JOOMLA (actual)                                          → LARAVEL (nuevo)
/es/noticias/890-tai-chi-la-disciplina-ancestral...     → /noticias/tai-chi-la-disciplina-ancestral...
/es/noticias/888-horneando-esperanza...                 → /noticias/horneando-esperanza...
/es/relatos/784-ando-ganas...                           → /relatos/ando-ganas...
/es/inicio/quienes-somos                                → /nosotros/quienes-somos
/es/inicio/historia                                     → /nosotros/historia
```

### Estrategia:
- Archivo de redirecciones 301 en Laravel (`routes/redirects.php`)
- Cada URL vieja de Joomla redirige a la nueva URL limpia
- Google transfiere el ranking automáticamente
- Se genera un nuevo `sitemap.xml` y se envía a Google Search Console

---

## 8. Multi-idioma

### Idiomas soportados:
- 🇪🇸 Español (principal)
- 🇧🇷 Portugués
- 🇮🇹 Italiano
- 🇫🇷 Francés
- 🇬🇧 Inglés

### Implementación:
- Prefijo en URL: `/es/noticias/...`, `/en/news/...`, `/pt/noticias/...`
- Filament Translatable Plugin para gestionar traducciones desde el admin
- Contenido por defecto en español; otros idiomas opcionales

---

## 9. Requisitos del servidor (Ferozo - DonWeb)

| Requisito | Necesario | DonWeb lo tiene |
|---|---|---|
| PHP 8.1+ | Sí | Sí (configurable desde panel) |
| MySQL 5.7+ | Sí | Sí |
| Composer | Sí | Vía SSH |
| Extensiones PHP (mbstring, xml, curl, gd) | Sí | Sí generalmente |
| Acceso SSH | Recomendado | Sí (confirmado) |
| SSL/HTTPS | Sí | Let's Encrypt gratis en DonWeb |
| Almacenamiento | ~500MB para el sitio | Depende del plan |
| Cron jobs | Para tareas programadas | Sí desde panel |

---

## 10. Cronograma estimado de desarrollo

```
FASE 1 - Estructura base (semana 1)
├── Instalar Laravel + Filament
├── Configurar base de datos
├── Modelos: Noticias, Relatos, Páginas, Áreas de trabajo
├── Panel admin básico funcionando
└── Subir a dev.cooperativaliberte.coop

FASE 2 - Diseño público (semana 2)
├── Layout principal (header, footer, nav)
├── Página de inicio
├── Página de noticias + artículo individual
├── Página de relatos
├── Responsive (mobile-first)
└── Revisar con el equipo

FASE 3 - Contenido y funcionalidades (semana 3)
├── Migrar noticias existentes de Joomla
├── Cargar contenido de páginas estáticas
├── Multi-idioma
├── SEO (meta tags, sitemap, redirects)
└── Integración Instagram

FASE 4 - Lanzamiento (semana 4)
├── Testing completo
├── Aprobación final
├── Cambio de dominio principal
├── Activar redirecciones 301
├── Enviar nuevo sitemap a Google
└── Eliminar Joomla
```

---

## 11. Lo que necesito para arrancar

- [ ] Crear subdominio `dev.cooperativaliberte.coop` en panel Ferozo
- [ ] Crear base de datos MySQL nueva (nombre, usuario, contraseña)
- [ ] Datos de acceso SSH (host, usuario, contraseña/key)
- [ ] Confirmar versión de PHP disponible (`php -v` por SSH)
- [ ] Logo de la cooperativa en alta resolución
- [ ] Fotos reales de las áreas de trabajo (las que tengan disponibles)
- [ ] Cuenta de Instagram Business (si quieren la publicación automática)
- [ ] Listado de noticias prioritarias a migrar (o migrar todas)
