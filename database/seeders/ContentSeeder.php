<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\Page;
use App\Models\WorkArea;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // === CATEGORÍAS ===
        $general = Category::create(['name' => 'General', 'slug' => 'general', 'sort_order' => 1]);
        $talleres = Category::create(['name' => 'Talleres', 'slug' => 'talleres', 'sort_order' => 2]);
        $cooperativa = Category::create(['name' => 'Cooperativa', 'slug' => 'cooperativa', 'sort_order' => 3]);

        // === ÁREAS DE TRABAJO ===
        WorkArea::create([
            'name' => 'Marroquinería',
            'slug' => 'marroquineria',
            'short_description' => 'Producción artesanal de carteras, billeteras, cinturones y accesorios de cuero de alta calidad.',
            'description' => '<p>El taller de marroquinería es uno de los pilares productivos de la cooperativa. Aquí se fabrican productos de cuero de manera artesanal: carteras, billeteras, cinturones, porta documentos y accesorios diversos.</p><p>Cada pieza pasa por un proceso de diseño, corte, costura y terminación que garantiza un producto de calidad profesional. Los miembros del taller aprenden un oficio con salida laboral real y desarrollan habilidades que los acompañarán toda la vida.</p><p>Nuestros productos se comercializan en ferias y puntos de venta, demostrando que el trabajo cooperativo genera valor real.</p>',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        WorkArea::create([
            'name' => 'Carpintería',
            'slug' => 'carpinteria',
            'short_description' => 'Fabricación de muebles, estanterías y piezas en madera con terminación profesional.',
            'description' => '<p>El taller de carpintería transforma la madera en productos funcionales y estéticos. Se fabrican muebles, estanterías, marcos, porta objetos y piezas a medida.</p><p>Los integrantes del área aprenden técnicas de corte, ensamblado, lijado y terminación que les permiten dominar el oficio completo. El taller cuenta con herramientas manuales y eléctricas que posibilitan una producción de calidad.</p><p>Cada mueble que sale del taller es testimonio de que las manos que trabajan con dignidad construyen mucho más que objetos.</p>',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        WorkArea::create([
            'name' => 'Herrería',
            'slug' => 'herreria',
            'short_description' => 'Trabajos en metal: rejas, estructuras, mobiliario y piezas herreras artesanales.',
            'description' => '<p>El taller de herrería trabaja el metal para crear desde estructuras utilitarias hasta piezas decorativas. Rejas, portones, muebles de hierro, parrillas y elementos estructurales son algunos de los productos que salen de este espacio.</p><p>Los miembros aprenden soldadura, corte, forjado y terminación de metales. Es un oficio de alta demanda laboral que brinda herramientas concretas para la reinserción profesional.</p>',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        WorkArea::create([
            'name' => 'Huerta orgánica',
            'slug' => 'huerta-organica',
            'short_description' => 'Cultivo de hortalizas y verduras orgánicas para consumo interno y venta.',
            'description' => '<p>La huerta orgánica de Liberté produce alimentos saludables sin agroquímicos. Se cultivan verduras de estación, aromáticas y hortalizas que abastecen la cocina de la cooperativa y se comercializan externamente.</p><p>El trabajo en la huerta conecta a los miembros con la tierra y con ciclos naturales de producción. Es un espacio de aprendizaje sobre agroecología, compostaje y alimentación saludable.</p><p>Contamos con el acompañamiento técnico del INTA para optimizar los procesos productivos y garantizar la calidad de nuestros productos.</p>',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        WorkArea::create([
            'name' => 'Apicultura',
            'slug' => 'apicultura',
            'short_description' => 'Producción de miel artesanal y derivados apícolas.',
            'description' => '<p>El área de apicultura gestiona colmenas propias para la producción de miel pura y artesanal. Los miembros aprenden el manejo completo del apiario: revisión de colmenas, cosecha, extracción y envasado.</p><p>La miel de Liberté es un producto natural de alta calidad que se comercializa bajo la marca de la cooperativa. Además de miel, se producen subproductos como propóleo y cera.</p><p>La apicultura representa un oficio sustentable y con proyección económica real para los integrantes de la cooperativa.</p>',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        WorkArea::create([
            'name' => 'Artesanías',
            'slug' => 'artesanias',
            'short_description' => 'Creación de piezas artesanales únicas: bijouterie, decoración y objetos de diseño.',
            'description' => '<p>El taller de artesanías es un espacio de creatividad donde se producen piezas únicas: bijouterie, objetos decorativos, souvenirs y elementos de diseño.</p><p>Cada artesano desarrolla su estilo propio, trabajando con materiales diversos como alambre, piedras, maderas recicladas y resinas. Los productos se venden en ferias locales y regionales.</p><p>Este espacio demuestra que la creatividad no tiene muros y que el arte puede ser también una fuente de sustento digno.</p>',
            'is_active' => true,
            'sort_order' => 6,
        ]);

        // === PÁGINA: QUIÉNES SOMOS ===
        Page::create([
            'title' => 'Quiénes somos',
            'slug' => 'quienes-somos',
            'body' => '<p class="text-xl leading-relaxed">Somos Liberté, un emprendimiento 100% autogestionado por presos en la cárcel de máxima seguridad de Batán y desde 2021 formalmente una cooperativa de trabajo.</p>

<h2>Nuestra historia</h2>
<p>Iniciamos operaciones en 2014 para atender necesidades fundamentales como empleo, capacitación, recreación y alimentación. Hemos crecido desde dos personas hasta aproximadamente doscientos miembros actualmente.</p>

<p>La autogestión es el eje central de nuestra estrategia para mejorar la calidad de vida y restaurar dignidad. El crecimiento se ha logrado mediante el trabajo conjunto de integrantes, personas e instituciones que creen en esta iniciativa.</p>

<p>Hemos avanzado a pesar de obstáculos importantes mediante el diálogo, convirtiéndonos en parte fundadora del Comité de Prevención y Solución de Conflictos de la institución penitenciaria.</p>

<h2>Lo que hacemos</h2>
<p>Nuestras áreas productivas incluyen: marroquinería, carpintería, herrería, artesanías, apicultura y huerta orgánica. Cada área es un espacio real de producción y aprendizaje de oficios.</p>

<h2>Quiénes nos acompañan</h2>
<p>Contamos con el respaldo institucional de la Procuración Penitenciaria de la Nación, la Federación Argentina de Cooperativas de Crédito, el INTA, la Universidad Nacional de Mar del Plata y la agrupación Víctimas por la Paz.</p>

<p>Recordamos con gratitud al Juez Mario Juliano, cuyo apoyo fue fundamental para el desarrollo de la cooperativa.</p>

<h2>Nuestra misión</h2>
<p class="text-lg font-medium text-primary-500">Trabajar por la recuperación de los derechos y la dignidad en las cárceles, con el objetivo de crear un futuro en el que las personas puedan integrarse plenamente en la sociedad a través del trabajo cooperativo.</p>',
            'template' => 'default',
            'is_active' => true,
            'meta_title' => 'Quiénes somos - CooperativaLiberté',
            'meta_description' => 'Somos Liberté, un emprendimiento 100% autogestionado. Desde 2014 transformamos realidades a través del trabajo cooperativo.',
            'sort_order' => 1,
        ]);

        Page::create([
            'title' => 'Historia',
            'slug' => 'historia',
            'body' => '<p>La cooperativa Liberté nació en 2014 dentro de la Unidad Penal N.° 15 de Batán, Mar del Plata. Lo que comenzó como una iniciativa de dos personas se convirtió en un movimiento cooperativo de más de 200 miembros.</p>

<h2>2014 - Los inicios</h2>
<p>Dos personas dentro de la unidad penitenciaria deciden organizarse para generar trabajo productivo. Nace el germen de lo que sería Liberté.</p>

<h2>2014-2020 - Crecimiento</h2>
<p>Se suman progresivamente nuevos miembros y se abren talleres de marroquinería, carpintería y herrería. El emprendimiento crece orgánicamente, ganando reconocimiento interno y externo.</p>

<h2>2021 - Formalización</h2>
<p>El emprendimiento se constituye formalmente como Cooperativa de Trabajo Liberté Ltda., consolidando su estructura organizativa y su identidad cooperativa.</p>

<h2>2021-Presente - Consolidación</h2>
<p>Se amplían las áreas de trabajo incorporando huerta orgánica, apicultura y artesanías. Se establecen alianzas institucionales con el INTA, la UNMdP y la Federación Argentina de Cooperativas. La cooperativa supera los 200 miembros.</p>',
            'template' => 'default',
            'is_active' => true,
            'meta_title' => 'Historia - CooperativaLiberté',
            'meta_description' => 'La historia de la cooperativa Liberté: desde 2014 construyendo futuro a través del trabajo.',
            'sort_order' => 2,
        ]);

        // === NOTICIAS (migradas del sitio actual) ===
        News::create([
            'title' => 'Tai Chí: La Disciplina Ancestral que Transforma Vidas en Liberté',
            'slug' => 'tai-chi-la-disciplina-ancestral-que-transforma-vidas-en-liberte',
            'excerpt' => 'El profesor Raúl Sergio Álvarez compartió su pasión por el Tai Chí en el salón de Liberté, destacando la importancia de mover el cuerpo y la mente.',
            'body' => '<p>El estudio de la radio Aires de Liberté recibió al profesor de Tai Chí, Raúl Sergio Álvarez, quien compartió su pasión por esta disciplina ancestral en el salón de Liberté.</p>

<h2>La Importancia de Mover el Cuerpo y la Mente</h2>
<p>El profesor Álvarez destacó la importancia de incorporar el Tai Chí en la vida diaria, especialmente para personas mayores, ya que ayuda a mantener la flexibilidad y a prevenir lesiones.</p>

<h2>Un Camino hacia la Salud y la Defensa Personal</h2>
<p>El Tai Chí no solo es un ejercicio físico, sino también un sistema autocurativo y una forma de defensa personal. El profesor Álvarez compartió su experiencia y conocimientos con un grupo de personas que se reunieron en Liberté.</p>

<h2>Un Legado de Salud y Bienestar</h2>
<p>La disciplina del Tai Chí puede ser practicada por personas de todas las edades y es una forma de mantener la salud y el bienestar en la vida diaria.</p>

<p>Agradecemos al profesor Raúl Sergio Álvarez por compartir su conocimiento y pasión por el Tai Chí en Liberté. Su presencia es un regalo para nuestra comunidad.</p>

<p><em>Fuente: Radio Aires de Liberté</em></p>',
            'category_id' => $talleres->id,
            'user_id' => 1,
            'status' => 'published',
            'published_at' => '2025-12-18 10:00:00',
            'is_featured' => true,
        ]);

        News::create([
            'title' => 'Horneando esperanza: Silvana Oliva impartió taller de panadería en Liberté',
            'slug' => 'horneando-esperanza-silvana-oliva-taller-panaderia-liberte',
            'excerpt' => 'La emprendedora de Miramar visitó la cooperativa para compartir su experiencia en elaboración de pan con masa madre casera.',
            'body' => '<p>Silvana Oliva, emprendedora de Miramar, visitó la cooperativa Liberté para compartir su experiencia en elaboración de pan con masa madre casera. Durante su intervención en Radio Aires de Liberté, expresó que "hace pan" como forma de vida.</p>

<p>Desde julio de 2018, posee habilitación PUPA (Pequeñas Unidades Productivas Alimenticias), que le permite elaborar y comercializar productos desde su cocina siguiendo normativas sanitarias. Cuenta con autorizaciones municipales y provinciales para vender en la Feria Verde de Miramar.</p>

<p>Oliva enfatiza el trabajo con productos de "kilómetro 0" para reducir costos y fortalecer economías locales. La masa madre que utiliza es un fermento natural con agua y harina que facilita la digestión y genera productos más saludables.</p>

<p>Durante el taller en Liberté preparó pre-pizzas, hogazas y focaccia. Destacó que fue su primera experiencia en un espacio penitenciario, sorprendiéndose gratamente al observar que la realidad del lugar "dista mucho de los estereotipos."</p>

<p>Concluyó reflexionando que los prejuicios "no son sustentables" cuando se experimenta directamente la realidad.</p>',
            'category_id' => $talleres->id,
            'user_id' => 1,
            'status' => 'published',
            'published_at' => '2025-12-17 10:00:00',
        ]);

        News::create([
            'title' => 'Cooperativa y Comunidad Liberté igual a Resistencia',
            'slug' => 'cooperativa-y-comunidad-liberte-igual-a-resistencia',
            'excerpt' => 'La cooperativa Liberté sigue demostrando que la organización colectiva y el trabajo son el camino hacia la transformación real.',
            'body' => '<p>La cooperativa y comunidad Liberté continúa su camino de resistencia y construcción colectiva dentro de la Unidad Penal N.° 15 de Batán.</p>

<p>En un contexto donde las dificultades son cotidianas, la organización cooperativa se presenta como una herramienta concreta de transformación. Cada taller, cada producto terminado, cada nuevo miembro que se suma es una muestra de que otro camino es posible.</p>

<p>La resistencia de Liberté no es confrontación, es construcción. Es demostrar con hechos que el trabajo cooperativo dignifica, que la producción de calidad es posible en cualquier contexto, y que la autogestión es el mejor camino hacia la autonomía.</p>

<p>Con más de 200 miembros y seis áreas productivas activas, Liberté es hoy un referente de lo que se puede lograr cuando la voluntad colectiva se organiza con un objetivo claro: transformar realidades a través del trabajo.</p>',
            'category_id' => $cooperativa->id,
            'user_id' => 1,
            'status' => 'published',
            'published_at' => '2025-12-02 10:00:00',
        ]);

        News::create([
            'title' => 'Saberes Libres: aprendizaje sobre masa madre en la cooperativa',
            'slug' => 'saberes-libres-aprendizaje-masa-madre-cooperativa',
            'excerpt' => 'El programa Saberes Libres continúa acercando conocimientos prácticos a los miembros de la cooperativa.',
            'body' => '<p>El programa Saberes Libres en Liberté sumó una nueva edición centrada en el aprendizaje sobre masa madre y panificación artesanal.</p>

<p>Este tipo de talleres forman parte de la estrategia de la cooperativa de acercar saberes prácticos y productivos que puedan convertirse en herramientas de trabajo reales. La elaboración de pan con masa madre no solo es un oficio con demanda creciente, sino que representa una filosofía de producción saludable y sustentable.</p>

<p>Los participantes aprendieron desde la preparación del fermento natural hasta la cocción de distintos tipos de pan, integrando teoría y práctica en un espacio de intercambio genuino.</p>

<p>Saberes Libres es una iniciativa que demuestra que el conocimiento compartido multiplica oportunidades.</p>',
            'category_id' => $talleres->id,
            'user_id' => 1,
            'status' => 'published',
            'published_at' => '2025-11-10 10:00:00',
        ]);

    }
}
