<?php

namespace Database\Seeders;

use App\Models\Destino;
use App\Models\Paquete;
use Illuminate\Database\Seeder;

class DestinosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Catálogo de destinos base con IATA y Aeropuerto
        $destinosBase = [
            ['nombre' => 'Lima', 'codigo_iata' => 'LIM', 'aeropuerto' => 'Aeropuerto Internacional Jorge Chávez', 'pais' => 'Perú', 'descripcion' => 'Capital gastronómica de América y centro histórico colonial.', 'imagen' => 'lima.jpg'],
            ['nombre' => 'Cusco', 'codigo_iata' => 'CUZ', 'aeropuerto' => 'Aeropuerto Internacional Alejandro Velasco Astete', 'pais' => 'Perú', 'descripcion' => 'Capital histórica del Imperio Inca y puerta de entrada a Machu Picchu.', 'imagen' => 'cusco.jpg'],
            ['nombre' => 'Arequipa', 'codigo_iata' => 'AQP', 'aeropuerto' => 'Aeropuerto Internacional Alfredo Rodríguez Ballón', 'pais' => 'Perú', 'descripcion' => 'La Ciudad Blanca de sillar al pie del imponente volcán Misti.', 'imagen' => 'arequipa.jpg'],
            ['nombre' => 'Tarapoto', 'codigo_iata' => 'TPP', 'aeropuerto' => 'Aeropuerto Cadete FAP Guillermo del Castillo Paredes', 'pais' => 'Perú', 'descripcion' => 'Naturaleza amazónica, cascadas cristalinas y exuberante vegetación.', 'imagen' => 'tarapoto.jpg'],
            ['nombre' => 'Máncora', 'codigo_iata' => 'TYL', 'aeropuerto' => 'Aeropuerto Capitán FAP Víctor Montes Arias (Talara)', 'pais' => 'Perú', 'descripcion' => 'Playas soleadas, surf y vida marina en el norte peruano.', 'imagen' => 'mancora.jpg'],
            ['nombre' => 'Puno', 'codigo_iata' => 'JUL', 'aeropuerto' => 'Aeropuerto Internacional Inca Manco Cápac (Juliaca)', 'pais' => 'Perú', 'descripcion' => 'Tradiciones folclóricas a orillas del legendario Lago Titicaca.', 'imagen' => 'puno.jpg'],
            ['nombre' => 'Ica', 'codigo_iata' => 'ICG', 'aeropuerto' => 'Aeródromo Las Dunas', 'pais' => 'Perú', 'descripcion' => 'Oasis de la Huacachina, viñedos tradicionales y dunas desérticas.', 'imagen' => 'ica.jpg'],
            ['nombre' => 'Fukuoka', 'codigo_iata' => 'FUK', 'aeropuerto' => 'Aeropuerto de Fukuoka', 'pais' => 'Japón', 'descripcion' => 'Ciudad costera de Japón famosa por sus templos antiguos, playas y gastronomía yatai.', 'imagen' => 'fukuoka.jpg'],
            ['nombre' => 'Tokio', 'codigo_iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'descripcion' => 'Fusión fascinante entre tecnología futurista y templos milenarios.', 'imagen' => 'tokio.jpg'],
            ['nombre' => 'Cancún', 'codigo_iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'descripcion' => 'Playas paradisíacas de arena blanca y aguas turquesas del Caribe.', 'imagen' => 'cancun.jpg'],
            ['nombre' => 'Ciudad de México', 'codigo_iata' => 'MEX', 'aeropuerto' => 'Aeropuerto Internacional Benito Juárez', 'pais' => 'México', 'descripcion' => 'Metrópolis colonial y moderna rica en gastronomía y museos.', 'imagen' => 'cdmx.jpg'],
            ['nombre' => 'Cartagena', 'codigo_iata' => 'CTG', 'aeropuerto' => 'Aeropuerto Internacional Rafael Núñez', 'pais' => 'Colombia', 'descripcion' => 'Ciudad amurallada, historia colonial y encanto caribeño.', 'imagen' => 'cartagena.jpg'],
            ['nombre' => 'Buenos Aires', 'codigo_iata' => 'EZE', 'aeropuerto' => 'Aeropuerto Internacional Ministro Pistarini (Ezeiza)', 'pais' => 'Argentina', 'descripcion' => 'Cultura tanguera, arquitectura europea y gastronomía única.', 'imagen' => 'buenosaires.jpg'],
            ['nombre' => 'Río de Janeiro', 'codigo_iata' => 'GIG', 'aeropuerto' => 'Aeropuerto Internacional de Galeão', 'pais' => 'Brasil', 'descripcion' => 'Playas icónicas de Copacabana, el Cristo Redentor y el Pan de Azúcar.', 'imagen' => 'rio.jpg'],
            ['nombre' => 'Santiago', 'codigo_iata' => 'SCL', 'aeropuerto' => 'Aeropuerto Internacional Arturo Merino Benítez', 'pais' => 'Chile', 'descripcion' => 'Moderna capital enmarcada por la majestuosa cordillera de los Andes.', 'imagen' => 'santiago.jpg'],
            ['nombre' => 'Miami', 'codigo_iata' => 'MIA', 'aeropuerto' => 'Aeropuerto Internacional de Miami', 'pais' => 'Estados Unidos', 'descripcion' => 'Playas tropicales, distritos art déco y ambiente cosmopolita.', 'imagen' => 'miami.jpg'],
            ['nombre' => 'Nueva York', 'codigo_iata' => 'JFK', 'aeropuerto' => 'Aeropuerto Internacional John F. Kennedy', 'pais' => 'Estados Unidos', 'descripcion' => 'La ciudad que nunca duerme con sus famosos rascacielos y teatros.', 'imagen' => 'ny.jpg'],
            ['nombre' => 'Madrid', 'codigo_iata' => 'MAD', 'aeropuerto' => 'Aeropuerto Adolfo Suárez Madrid-Barajas', 'pais' => 'España', 'descripcion' => 'Grandes plazas, el Palacio Real y museos de arte de primer nivel.', 'imagen' => 'madrid.jpg'],
            ['nombre' => 'París', 'codigo_iata' => 'CDG', 'aeropuerto' => 'Aeropuerto de París-Charles de Gaulle', 'pais' => 'Francia', 'descripcion' => 'La Ciudad de la Luz, la Torre Eiffel y paseos por el río Sena.', 'imagen' => 'paris.jpg'],
            ['nombre' => 'Roma', 'codigo_iata' => 'FCO', 'aeropuerto' => 'Aeropuerto de Roma-Fiumicino Leonardo da Vinci', 'pais' => 'Italia', 'descripcion' => 'La Ciudad Eterna, cuna del Coliseo, el Vaticano y la Fontana di Trevi.', 'imagen' => 'roma.jpg'],
            ['nombre' => 'Londres', 'codigo_iata' => 'LHR', 'aeropuerto' => 'Aeropuerto de Londres-Heathrow', 'pais' => 'Reino Unido', 'descripcion' => 'Historia milenaria, el Big Ben, el río Támesis y vida cultural activa.', 'imagen' => 'londres.jpg'],
            ['nombre' => 'Santorini', 'codigo_iata' => 'JTR', 'aeropuerto' => 'Aeropuerto Nacional de Santorini', 'pais' => 'Grecia', 'descripcion' => 'Casas blancas en acantilados sobre el mar Egeo y atardeceres mágicos.', 'imagen' => 'santorini.jpg'],
            ['nombre' => 'Bali', 'codigo_iata' => 'DPS', 'aeropuerto' => 'Aeropuerto Internacional Ngurah Rai', 'pais' => 'Indonesia', 'descripcion' => 'Isla espiritual de templos budistas, arrozales y playas tropicales.', 'imagen' => 'bali.jpg'],
            ['nombre' => 'Bangkok', 'codigo_iata' => 'BKK', 'aeropuerto' => 'Aeropuerto Internacional Suvarnabhumi', 'pais' => 'Tailandia', 'descripcion' => 'Templos dorados, mercados flotantes y rica gastronomía callejera.', 'imagen' => 'bangkok.jpg'],
            ['nombre' => 'Dubái', 'codigo_iata' => 'DXB', 'aeropuerto' => 'Aeropuerto Internacional de Dubái', 'pais' => 'Emiratos Árabes Unidos', 'descripcion' => 'Arquitectura vanguardista con el Burj Khalifa y compras de lujo.', 'imagen' => 'dubai.jpg'],
            ['nombre' => 'El Cairo', 'codigo_iata' => 'CAI', 'aeropuerto' => 'Aeropuerto Internacional de El Cairo', 'pais' => 'Egipto', 'descripcion' => 'Las legendarias pirámides de Guiza, la Esfinge y el río Nilo.', 'imagen' => 'elcairo.jpg'],
            ['nombre' => 'Marrakech', 'codigo_iata' => 'RAK', 'aeropuerto' => 'Aeropuerto de Marrakech-Menara', 'pais' => 'Marruecos', 'descripcion' => 'Zocos llenos de color, palacios históricos y la plaza Jemaa el-Fna.', 'imagen' => 'marrakech.jpg'],
            ['nombre' => 'Sídney', 'codigo_iata' => 'SYD', 'aeropuerto' => 'Aeropuerto Internacional Kingsford Smith', 'pais' => 'Australia', 'descripcion' => 'La mundialmente famosa Ópera de Sídney y playas como Bondi Beach.', 'imagen' => 'sidney.jpg'],
        ];

        // 2. Registrar o actualizar los destinos
        foreach ($destinosBase as $datos) {
            Destino::updateOrCreate(
                ['nombre' => $datos['nombre']],
                $datos
            );
        }

        // 3. Vincular los paquetes creados previamente con su destino_id correspondiente
        $destinosRegistrados = Destino::all();

        foreach ($destinosRegistrados as $destino) {
            Paquete::where('destino', $destino->nombre)
                ->update(['destino_id' => $destino->id]);
        }
    }
}
