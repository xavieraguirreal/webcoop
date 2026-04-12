<?php

namespace Database\Seeders;

use App\Models\WorkArea;
use Illuminate\Database\Seeder;

class WorkAreaSeeder extends Seeder
{
    public function run(): void
    {
        WorkArea::truncate();

        // --- Talleres Productivos ---
        WorkArea::create([
            'name' => 'Carpintería Industrial', 'slug' => 'carpinteria-industrial', 'group' => 'talleres-productivos',
            'short_description' => 'Fabricación de muebles, estanterías y piezas en madera con terminación profesional.',
            'description' => '<p>El taller de carpintería transforma la madera en productos funcionales y estéticos. Se fabrican muebles, estanterías, marcos, porta objetos y piezas a medida.</p><p>Los integrantes del área aprenden técnicas de corte, ensamblado, lijado y terminación que les permiten dominar el oficio completo.</p>',
            'is_active' => true, 'sort_order' => 1,
        ]);
        WorkArea::create([
            'name' => 'Carpintería Láser', 'slug' => 'carpinteria-laser', 'group' => 'talleres-productivos',
            'short_description' => 'Corte y grabado láser sobre madera, acrílico y otros materiales.',
            'description' => '<p>El taller de carpintería láser combina tecnología y artesanía para producir piezas de precisión: señalización, souvenirs, objetos decorativos y cortes personalizados.</p>',
            'is_active' => true, 'sort_order' => 2,
        ]);
        WorkArea::create([
            'name' => 'Herrería', 'slug' => 'herreria', 'group' => 'talleres-productivos',
            'short_description' => 'Trabajos en metal: rejas, estructuras, mobiliario y piezas herreras artesanales.',
            'description' => '<p>El taller de herrería trabaja el metal para crear desde estructuras utilitarias hasta piezas decorativas. Rejas, portones, muebles de hierro, parrillas y elementos estructurales.</p><p>Los miembros aprenden soldadura, corte, forjado y terminación de metales.</p>',
            'is_active' => true, 'sort_order' => 3,
        ]);
        WorkArea::create([
            'name' => 'Costura', 'slug' => 'costura', 'group' => 'talleres-productivos',
            'short_description' => 'Confección de indumentaria, reparaciones y trabajos textiles.',
            'description' => '<p>El taller de costura produce indumentaria y realiza reparaciones textiles. Los miembros aprenden corte, confección y manejo de máquinas industriales.</p>',
            'is_active' => true, 'sort_order' => 4,
        ]);
        WorkArea::create([
            'name' => 'Marroquinería', 'slug' => 'marroquineria', 'group' => 'talleres-productivos',
            'short_description' => 'Producción artesanal de carteras, billeteras, cinturones y accesorios de cuero.',
            'description' => '<p>El taller de marroquinería fabrica productos de cuero de manera artesanal: carteras, billeteras, cinturones, porta documentos y accesorios diversos.</p><p>Cada pieza pasa por un proceso de diseño, corte, costura y terminación que garantiza calidad profesional.</p>',
            'is_active' => true, 'sort_order' => 5,
        ]);
        WorkArea::create([
            'name' => 'Artesanías', 'slug' => 'artesanias', 'group' => 'talleres-productivos',
            'short_description' => 'Piezas artesanales únicas: bijouterie, decoración y objetos de diseño.',
            'description' => '<p>El taller de artesanías es un espacio de creatividad donde se producen piezas únicas: bijouterie, objetos decorativos, souvenirs y elementos de diseño.</p>',
            'is_active' => true, 'sort_order' => 6,
        ]);
        WorkArea::create([
            'name' => 'Cocina Comunitaria', 'slug' => 'cocina-comunitaria', 'group' => 'talleres-productivos',
            'short_description' => 'Elaboración de alimentos con autorización PUPAA.',
            'description' => '<p>La cocina comunitaria de Liberté está autorizada como Pequeña Unidad Productiva de Alimentos Artesanales (PUPAA). Se elaboran productos alimenticios que se comercializan bajo las normas sanitarias vigentes.</p>',
            'is_active' => true, 'sort_order' => 7,
        ]);
        WorkArea::create([
            'name' => 'Panadería Comunitaria', 'slug' => 'panaderia-comunitaria', 'group' => 'talleres-productivos',
            'short_description' => 'Producción de pan y productos de panadería con autorización PUPAA.',
            'description' => '<p>La panadería comunitaria produce pan artesanal y productos de panificación con autorización PUPAA. Los miembros aprenden técnicas de elaboración de masa madre, panificación y pastelería.</p>',
            'is_active' => true, 'sort_order' => 8,
        ]);

        // --- Producción Agropecuaria ---
        WorkArea::create([
            'name' => 'Huertas', 'slug' => 'huertas', 'group' => 'produccion-agropecuaria',
            'short_description' => 'Cultivo de hortalizas y verduras orgánicas a cielo abierto.',
            'description' => '<p>Las huertas de Liberté producen alimentos saludables sin agroquímicos. Se cultivan verduras de estación, aromáticas y hortalizas para consumo interno y venta.</p><p>Contamos con acompañamiento técnico del INTA.</p>',
            'is_active' => true, 'sort_order' => 9,
        ]);
        WorkArea::create([
            'name' => 'Invernaderos', 'slug' => 'invernaderos', 'group' => 'produccion-agropecuaria',
            'short_description' => 'Producción hortícola bajo cubierta durante todo el año.',
            'description' => '<p>Los invernaderos permiten extender la temporada de cultivo y producir verduras y plantines durante todo el año, protegidos de las condiciones climáticas adversas.</p>',
            'is_active' => true, 'sort_order' => 10,
        ]);
        WorkArea::create([
            'name' => 'Gallinero', 'slug' => 'gallinero', 'group' => 'produccion-agropecuaria',
            'short_description' => 'Cría de aves para producción de huevos y carne.',
            'description' => '<p>El gallinero de Liberté contribuye a la soberanía alimentaria de la cooperativa con producción de huevos y carne de ave para consumo interno.</p>',
            'is_active' => true, 'sort_order' => 11,
        ]);
        WorkArea::create([
            'name' => 'Conejera', 'slug' => 'conejera', 'group' => 'produccion-agropecuaria',
            'short_description' => 'Cría de conejos para producción de carne.',
            'description' => '<p>La conejera forma parte del sistema de producción animal de la cooperativa, complementando la dieta con carne de conejo y contribuyendo a la autosuficiencia alimentaria.</p>',
            'is_active' => true, 'sort_order' => 12,
        ]);
        WorkArea::create([
            'name' => 'Apiario', 'slug' => 'apiario', 'group' => 'produccion-agropecuaria',
            'short_description' => 'Producción de miel artesanal y derivados apícolas.',
            'description' => '<p>El apiario gestiona colmenas propias para la producción de miel pura y artesanal. Los miembros aprenden el manejo completo: revisión de colmenas, cosecha, extracción y envasado.</p>',
            'is_active' => true, 'sort_order' => 13,
        ]);

        // --- Educación y Formación ---
        WorkArea::create([
            'name' => 'Universidad Liberté', 'slug' => 'universidad-liberte', 'group' => 'educacion-formacion',
            'short_description' => 'Formación académica y profesional para los integrantes de Liberté.',
            'description' => '<p>La Universidad Liberté (ULIB) es el brazo educativo del proyecto. Ofrece formación académica, capacitación profesional y programas de desarrollo personal para los integrantes.</p>',
            'is_active' => true, 'sort_order' => 14,
        ]);
        WorkArea::create([
            'name' => 'Formaciones Presenciales', 'slug' => 'formaciones-presenciales', 'group' => 'educacion-formacion',
            'short_description' => 'Talleres y capacitaciones organizados por ULIB en el espacio de la cooperativa.',
            'description' => '<p>Las formaciones presenciales son talleres y capacitaciones que la Universidad Liberté organiza dentro del espacio de la cooperativa, abarcando oficios, habilidades blandas y conocimientos técnicos.</p>',
            'is_active' => true, 'sort_order' => 15,
        ]);
        WorkArea::create([
            'name' => 'Biblioteca', 'slug' => 'biblioteca', 'group' => 'educacion-formacion',
            'short_description' => 'Espacio de lectura y acceso a material educativo.',
            'description' => '<p>La biblioteca es un espacio de lectura, estudio y acceso a material educativo para todos los integrantes. Un recurso fundamental para el desarrollo personal y la formación continua.</p>',
            'is_active' => true, 'sort_order' => 16,
        ]);

        // --- Bienestar y Comunidad ---
        WorkArea::create([
            'name' => 'Programa de Salud Mental', 'slug' => 'programa-salud-mental', 'group' => 'bienestar-comunidad',
            'short_description' => 'Acompañamiento psicológico y contención emocional.',
            'description' => '<p>El Programa de Salud Mental funciona dentro de la cooperativa brindando acompañamiento psicológico, contención emocional y herramientas para el bienestar de los integrantes.</p>',
            'is_active' => true, 'sort_order' => 17,
        ]);
        WorkArea::create([
            'name' => 'Club Deportivo ULIB', 'slug' => 'club-deportivo-ulib', 'group' => 'bienestar-comunidad',
            'short_description' => 'Fútbol, boxeo, gimnasio, bochas, tejo y ajedrez.',
            'description' => '<p>El Club Deportivo ULIB organiza actividades deportivas presenciales en el espacio de la cooperativa: fútbol, boxeo, gimnasio, bochas, tejo y ajedrez. El deporte como herramienta de integración y bienestar.</p>',
            'is_active' => true, 'sort_order' => 18,
        ]);
        WorkArea::create([
            'name' => 'Voluntariado para la Integración Social', 'slug' => 'voluntariado-integracion-social', 'group' => 'bienestar-comunidad',
            'short_description' => 'Programa de voluntariado que acompaña procesos de reinserción.',
            'description' => '<p>El Voluntariado para la Integración Social, coordinado por ULIB, acompaña a los integrantes en sus procesos de reinserción social a través de la participación activa de voluntarios externos.</p>',
            'is_active' => true, 'sort_order' => 19,
        ]);
        WorkArea::create([
            'name' => 'Radio Aires de Liberté', 'slug' => 'radio-aires-de-liberte', 'group' => 'bienestar-comunidad',
            'short_description' => 'Emisora radial con transmisión al aire desde el espacio de la cooperativa.',
            'description' => '<p>Radio Aires de Liberté transmite al aire desde el espacio de la cooperativa. Es un medio de comunicación propio que da voz a los integrantes y conecta con la comunidad exterior.</p>',
            'is_active' => true, 'sort_order' => 20,
        ]);
        WorkArea::create([
            'name' => 'Estanque', 'slug' => 'estanque', 'group' => 'bienestar-comunidad',
            'short_description' => 'Espacio de esparcimiento con peces y patos.',
            'description' => '<p>El estanque es un espacio de paz y contemplación dentro de Liberté. Con peces koi y patos, es un oasis que invita a la reflexión y al descanso, demostrando que la transformación también necesita espacios de serenidad.</p>',
            'is_active' => true, 'sort_order' => 21,
        ]);
        WorkArea::create([
            'name' => 'Comité de Convivencia Mario Juliano', 'slug' => 'comite-convivencia-mario-juliano', 'group' => 'bienestar-comunidad',
            'short_description' => 'Órgano de gestión y resolución de conflictos.',
            'description' => '<p>El Comité de Convivencia Mario Juliano es el órgano encargado de la prevención y resolución de conflictos. Lleva el nombre del juez que fue fundamental para el desarrollo de la cooperativa. Es transversal a toda la comunidad de Liberté.</p>',
            'is_active' => true, 'sort_order' => 22,
        ]);
    }
}
