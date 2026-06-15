<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cine;

class CinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cines = [
            [
                'nombre' => 'Cinépolis plaza cristal - C. Lázaro Cárdenas 40 a, Francisco Villa - C. Lázaro Cárdenas 40 a, Francisco Villa',
                'ubicacion' => 'C. Lázaro Cárdenas 40 a, Francisco Villa',
                'url' => null,
                'ciudad' => 'Xalapa, Veracruz',
                'nombre_cine' => 'Cinépolis plaza cristal',
            ],
            [
                'nombre' => 'Cinemex - Soriana Hiper - Soriana Hiper - Córdoba, Veracruz',
                'ubicacion' => 'Soriana Hiper',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Córdoba, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinemex - Gran Patio - Gran Patio - Poza Rica, Veracruz',
                'ubicacion' => 'Gran Patio',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Poza Rica, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Shangi-la - Plaza Shangi-la - Córdoba, Veracruz',
                'ubicacion' => 'Plaza Shangi-la',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Córdoba, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Shangi-la - Plaza Shangi-la - Fortín de las Flores, Veracruz',
                'ubicacion' => 'Plaza Shangi-la',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Fortín de las Flores, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Chedraui, Andrés Vázquez, Adolfo Ruiz Cortines',
                'ubicacion' => 'Chedraui, Andrés Vázquez, Adolfo Ruiz Cortines',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Martínez de la Torre, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Valle - Plaza Valle - Orizaba, Veracruz',
                'ubicacion' => 'Plaza Valle',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Orizaba, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Crystal - Plaza Crystal - Tuxpan, Veracruz',
                'ubicacion' => 'Plaza Crystal',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Tuxpan, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Crystal - Plaza Crystal - Xalapa, Veracruz',
                'ubicacion' => 'Plaza Crystal',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Museo/Plaza Américas - Plaza Museo/Plaza Américas - Xalapa, Veracruz',
                'ubicacion' => 'Plaza Museo/Plaza Américas',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinetix - Plaza Ánimas/Plaza Zaragoza - Plaza Ánimas/Plaza Zaragoza - Xalapa, Veracruz',
                'ubicacion' => 'Plaza Ánimas/Plaza Zaragoza',
                'url' => 'www.cinetix.mx',
                'ciudad' => 'Xalapa, Veracruz',
                'nombre_cine' => 'Cinetix',
            ],
            [
                'nombre' => 'Cinebox - Calle Unión, entre Michoacán y Oaxaca, Col. México',
                'ubicacion' => 'Calle Unión, entre Michoacán y Oaxaca, Col. México',
                'url' => 'www.cinebox.mx',
                'ciudad' => 'Poza Rica, Veracruz',
                'nombre_cine' => 'Cinebox',
            ],
            [
                'nombre' => 'Cinemex - Soriana Hiper Poza Rica. Av. Puebla 502, Palma Sola',
                'ubicacion' => 'Soriana Hiper Poza Rica. Av. Puebla 502, Palma Sola',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Poza Rica, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinepolis - Plaza Crystal. Blvd. Demetrio Ruiz Malerva 283, Tenechaco',
                'ubicacion' => 'Plaza Crystal. Blvd. Demetrio Ruiz Malerva 283, Tenechaco',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Tuxpan, Veracruz',
                'nombre_cine' => 'Cinepolis',
            ],
            [
                'nombre' => 'Henry Cinemas - Av. Benito Juárez 48, Centro - Tuxpan, Veracruz',
                'ubicacion' => 'Av. Benito Juárez 48, Centro',
                'url' => 'www.cinemashenry.com.mx',
                'ciudad' => 'Tuxpan, Veracruz',
                'nombre_cine' => 'Henry Cinemas',
            ],
            [
                'nombre' => 'Cinemas San Andrés - Ignacio de la Llave s/n 95700, San Andrés Tuxtla',
                'ubicacion' => 'Ignacio de la Llave s/n 95700, San Andrés Tuxtla',
                'url' => null,
                'ciudad' => 'San Andrés Tuxtla, Veracruz',
                'nombre_cine' => 'Cinemas San Andrés',
            ],
            [
                'nombre' => 'Cinemex - Plaza Andamar - Plaza Andamar - Boca del Río, Veracruz',
                'ubicacion' => 'Plaza Andamar',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Boca del Río, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinemex - Plaza Forum - Plaza Forum - Coatzacoalcos, Veracruz',
                'ubicacion' => 'Plaza Forum',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Coatzacoalcos, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Acaya - Plaza Acaya - Coatzacoalcos, Veracruz',
                'ubicacion' => 'Plaza Acaya',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Coatzacoalcos, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinemex - Blvd. Instituto Tecnológico #24, km. Col. Insurgentes Norte',
                'ubicacion' => 'Blvd. Instituto Tecnológico #24, km. Col. Insurgentes Norte',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Minatitlán, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinemex - Plaza Los Pinos; Las Palmas; Plaza Nuevo Veracruz',
                'ubicacion' => 'Plaza Los Pinos; Las Palmas; Plaza Nuevo Veracruz',
                'url' => 'www.cinemex.com',
                'ciudad' => 'Veracruz, Veracruz',
                'nombre_cine' => 'Cinemex',
            ],
            [
                'nombre' => 'Cinépolis - La Florida. Avenida Melchor Ocampo/Prolongación, Antonio Plaza Tamarindo',
                'ubicacion' => 'La Florida. Avenida Melchor Ocampo/Prolongación, Antonio Plaza Tamarindo',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Acayucan, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Las Américas - Plaza Las Américas - Boca del Río, Veracruz',
                'ubicacion' => 'Plaza Las Américas',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Boca del Río, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza Minatitlán - Plaza Minatitlán - Minatitlán, Veracruz',
                'ubicacion' => 'Plaza Minatitlán',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Minatitlán, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza del puerto - Plaza del puerto - Veracruz, Veracruz',
                'ubicacion' => 'Plaza del puerto',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Veracruz, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Plaza el dorado, los Tigres 151 A, las Gaviotas',
                'ubicacion' => 'Plaza el dorado, los Tigres 151 A, las Gaviotas',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Coatzacoalcos, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
            [
                'nombre' => 'Cinépolis - Chedraui, Calle Emiliano Zapata 10, Insurgentes Sur',
                'ubicacion' => 'Chedraui, Calle Emiliano Zapata 10, Insurgentes Sur',
                'url' => 'www.cinepolis.com.mx',
                'ciudad' => 'Minatitlán, Veracruz',
                'nombre_cine' => 'Cinépolis',
            ],
        ];

        foreach ($cines as $cine) {
            Cine::create($cine);
        }
    }
}