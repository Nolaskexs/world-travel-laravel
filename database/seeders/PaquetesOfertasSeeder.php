<?php

namespace Database\Seeders;

use App\Models\Oferta;
use App\Models\Paquete;
use Illuminate\Database\Seeder;

class PaquetesOfertasSeeder extends Seeder
{
    public function run(): void
    {
        // Datos académicos de demostración: precios base en USD por persona.
        // No son tarifas reales ni presupuestos con origen de vuelo definido.
        $paquetes = [
            [
                'titulo' => 'Cusco y Machu Picchu', 'destino' => 'Cusco',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Descubre la ciudad imperial, el Valle Sagrado y los paisajes de Machu Picchu.',
                'duracion_dias' => 5, 'precio' => 519,
                'imagen_url' => 'https://images.unsplash.com/photo-1526392060635-9d6019884377?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => true, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Encantos de Arequipa', 'destino' => 'Arequipa',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Recorre la Ciudad Blanca, sus miradores y la arquitectura del sur peruano.',
                'duracion_dias' => 3, 'precio' => 225,
                'imagen_url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Selva mágica de Tarapoto', 'destino' => 'Tarapoto',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Naturaleza amazónica, cascadas y excursiones en la selva peruana.',
                'duracion_dias' => 4, 'precio' => 349,
                'imagen_url' => 'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => true, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Escapada a Máncora', 'destino' => 'Máncora',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Disfruta de playas cálidas, mar y experiencias costeras.',
                'duracion_dias' => 4, 'precio' => 329,
                'imagen_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => false, 'destacado' => false,
            ],
            [
                'titulo' => 'Lago Titicaca y Puno', 'destino' => 'Puno',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Tradiciones del altiplano y una experiencia en el lago Titicaca.',
                'duracion_dias' => 3, 'precio' => 269,
                'imagen_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Paracas y Huacachina', 'destino' => 'Ica',
                'pais' => 'Perú', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Islas, dunas y aventuras en el desierto costero.',
                'duracion_dias' => 2, 'precio' => 169,
                'imagen_url' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Caribe mexicano en Cancún', 'destino' => 'Cancún',
                'pais' => 'México', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Playas de aguas turquesas, cultura maya y días de descanso en el Caribe.',
                'duracion_dias' => 7, 'precio' => 1290,
                'imagen_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Cultura y sabores de Ciudad de México', 'destino' => 'Ciudad de México',
                'pais' => 'México', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Descubre museos, plazas históricas, gastronomía y barrios llenos de vida.',
                'duracion_dias' => 5, 'precio' => 820,
                'imagen_url' => 'https://images.unsplash.com/photo-1512813195386-6cf811ad3542?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Cartagena colonial y Caribe', 'destino' => 'Cartagena',
                'pais' => 'Colombia', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Calles coloridas, historia colonial y una escapada al mar Caribe.',
                'duracion_dias' => 5, 'precio' => 760,
                'imagen_url' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Buenos Aires cultural', 'destino' => 'Buenos Aires',
                'pais' => 'Argentina', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Explora barrios históricos, arte, tango y gastronomía argentina.',
                'duracion_dias' => 6, 'precio' => 980,
                'imagen_url' => 'https://images.unsplash.com/photo-1485871981521-5b1fd3805eee?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Río de Janeiro inolvidable', 'destino' => 'Río de Janeiro',
                'pais' => 'Brasil', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Playas icónicas, miradores y paisajes que unen montaña y océano.',
                'duracion_dias' => 6, 'precio' => 1130,
                'imagen_url' => 'https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Santiago y los Andes', 'destino' => 'Santiago',
                'pais' => 'Chile', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Experiencias urbanas, gastronomía y vistas de la cordillera.',
                'duracion_dias' => 4, 'precio' => 699,
                'imagen_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Miami junto al océano', 'destino' => 'Miami',
                'pais' => 'Estados Unidos', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Playas, arquitectura art déco y una ciudad vibrante.',
                'duracion_dias' => 6, 'precio' => 1450,
                'imagen_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => false, 'destacado' => false,
            ],
            [
                'titulo' => 'Nueva York esencial', 'destino' => 'Nueva York',
                'pais' => 'Estados Unidos', 'continente' => 'America', 'moneda' => 'USD',
                'descripcion' => 'Rascacielos, museos, parques y las luces de Manhattan.',
                'duracion_dias' => 6, 'precio' => 1790,
                'imagen_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Madrid y sus grandes plazas', 'destino' => 'Madrid',
                'pais' => 'España', 'continente' => 'Europa', 'moneda' => 'USD',
                'descripcion' => 'Arquitectura, museos, barrios y sabores de la capital española.',
                'duracion_dias' => 7, 'precio' => 1650,
                'imagen_url' => 'https://images.unsplash.com/photo-1543783207-ec64e4d95325?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'París romántico', 'destino' => 'París',
                'pais' => 'Francia', 'continente' => 'Europa', 'moneda' => 'USD',
                'descripcion' => 'Recorre la Torre Eiffel, museos y los rincones de la Ciudad de la Luz.',
                'duracion_dias' => 7, 'precio' => 1890,
                'imagen_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Roma eterna', 'destino' => 'Roma',
                'pais' => 'Italia', 'continente' => 'Europa', 'moneda' => 'USD',
                'descripcion' => 'Arte, historia antigua y experiencias gastronómicas en Roma.',
                'duracion_dias' => 7, 'precio' => 1680,
                'imagen_url' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Londres entre historia y modernidad', 'destino' => 'Londres',
                'pais' => 'Reino Unido', 'continente' => 'Europa', 'moneda' => 'USD',
                'descripcion' => 'Museos, barrios y monumentos de la capital británica.',
                'duracion_dias' => 6, 'precio' => 1990,
                'imagen_url' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Santorini y el mar Egeo', 'destino' => 'Santorini',
                'pais' => 'Grecia', 'continente' => 'Europa', 'moneda' => 'USD',
                'descripcion' => 'Pueblos blancos, terrazas y atardeceres frente al mar Egeo.',
                'duracion_dias' => 7, 'precio' => 2160,
                'imagen_url' => 'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Tokio futurista y tradicional', 'destino' => 'Tokio',
                'pais' => 'Japón', 'continente' => 'Asia', 'moneda' => 'USD',
                'descripcion' => 'Templos, tecnología, gastronomía y barrios únicos de Japón.',
                'duracion_dias' => 8, 'precio' => 2590,
                'imagen_url' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Bali naturaleza y templos', 'destino' => 'Bali',
                'pais' => 'Indonesia', 'continente' => 'Asia', 'moneda' => 'USD',
                'descripcion' => 'Arrozales, templos, playas y descanso en una isla tropical.',
                'duracion_dias' => 8, 'precio' => 2080,
                'imagen_url' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Bangkok entre templos y sabores', 'destino' => 'Bangkok',
                'pais' => 'Tailandia', 'continente' => 'Asia', 'moneda' => 'USD',
                'descripcion' => 'Mercados, templos, canales y gastronomía tailandesa.',
                'duracion_dias' => 7, 'precio' => 1790,
                'imagen_url' => 'https://images.unsplash.com/photo-1508004680771-708b02aabdc0?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Dubái de otro nivel', 'destino' => 'Dubái',
                'pais' => 'Emiratos Árabes Unidos', 'continente' => 'Asia', 'moneda' => 'USD',
                'descripcion' => 'Arquitectura moderna, experiencias en el desierto y vida urbana.',
                'duracion_dias' => 6, 'precio' => 1980,
                'imagen_url' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Egipto y las pirámides', 'destino' => 'El Cairo',
                'pais' => 'Egipto', 'continente' => 'Africa', 'moneda' => 'USD',
                'descripcion' => 'Historia antigua, museos y monumentos emblemáticos de Egipto.',
                'duracion_dias' => 7, 'precio' => 1770,
                'imagen_url' => 'https://images.unsplash.com/photo-1539768942893-daf53e448371?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
            [
                'titulo' => 'Marrakech y el desierto', 'destino' => 'Marrakech',
                'pais' => 'Marruecos', 'continente' => 'Africa', 'moneda' => 'USD',
                'descripcion' => 'Mercados, palacios y paisajes del norte de África.',
                'duracion_dias' => 7, 'precio' => 1490,
                'imagen_url' => 'https://images.unsplash.com/photo-1597212720158-74c05b6be801?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => false,
            ],
            [
                'titulo' => 'Sídney y su bahía', 'destino' => 'Sídney',
                'pais' => 'Australia', 'continente' => 'Oceania', 'moneda' => 'USD',
                'descripcion' => 'Recorre la bahía, playas y los principales barrios de Sídney.',
                'duracion_dias' => 8, 'precio' => 2850,
                'imagen_url' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&w=1200&q=85',
                'incluye_vuelo' => false, 'incluye_hotel' => true,
                'incluye_tour' => true, 'destacado' => true,
            ],
        ];

        $ids = [];
        foreach ($paquetes as $datos) {
            $paquete = Paquete::updateOrCreate(
                ['titulo' => $datos['titulo']],
                $datos + ['activo' => true]
            );
            $ids[$datos['destino']] = $paquete->id;
        }

        // Descuentos de demostración. Se aplican sobre el precio USD del paquete.
        $promociones = [
            ['destino' => 'Cusco', 'titulo' => 'Machu Picchu con descuento', 'descuento_porcentaje' => 20],
            ['destino' => 'Tarapoto', 'titulo' => 'Aventura amazónica especial', 'descuento_porcentaje' => 25],
            ['destino' => 'Máncora', 'titulo' => 'Oferta de playa', 'descuento_porcentaje' => 15],
            ['destino' => 'Ica', 'titulo' => 'Escapada de fin de semana', 'descuento_porcentaje' => 10],
            ['destino' => 'Cancún', 'titulo' => 'Vacaciones en el Caribe mexicano', 'descuento_porcentaje' => 18],
            ['destino' => 'Cartagena', 'titulo' => 'Caribe colombiano especial', 'descuento_porcentaje' => 15],
            ['destino' => 'Río de Janeiro', 'titulo' => 'Río de Janeiro con descuento', 'descuento_porcentaje' => 17],
            ['destino' => 'Nueva York', 'titulo' => 'Escapada a Nueva York', 'descuento_porcentaje' => 12],
            ['destino' => 'París', 'titulo' => 'París: descubre Europa', 'descuento_porcentaje' => 20],
            ['destino' => 'Roma', 'titulo' => 'Oferta Italia histórica', 'descuento_porcentaje' => 15],
            ['destino' => 'Santorini', 'titulo' => 'Verano en Santorini', 'descuento_porcentaje' => 14],
            ['destino' => 'Tokio', 'titulo' => 'Tokio: experiencia de Japón', 'descuento_porcentaje' => 10],
            ['destino' => 'Bali', 'titulo' => 'Bali: aventura y relax', 'descuento_porcentaje' => 22],
            ['destino' => 'Dubái', 'titulo' => 'Dubái: escapada internacional', 'descuento_porcentaje' => 15],
            ['destino' => 'El Cairo', 'titulo' => 'Egipto: maravillas antiguas', 'descuento_porcentaje' => 18],
            ['destino' => 'Sídney', 'titulo' => 'Sídney: descubre Oceanía', 'descuento_porcentaje' => 10],
        ];

        foreach ($promociones as $datos) {
            Oferta::updateOrCreate(
                ['paquete_id' => $ids[$datos['destino']], 'titulo' => $datos['titulo']],
                [
                    'descuento_porcentaje' => $datos['descuento_porcentaje'],
                    'fecha_inicio' => now()->subDay()->toDateString(),
                    'fecha_fin' => now()->addDays(45)->toDateString(),
                    'activo' => true,
                ]
            );
        }
    }
}
