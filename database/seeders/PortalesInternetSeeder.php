<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortalInternet;

class PortalesInternetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portales = [
            [
                'nombre' => 'Elementos MX',
                'url' => 'https://elementosmx.com',
                'ciudad' => 'Tuxpan, Veracruz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Noroeste',
                'url' => 'http://noreste.net',
                'ciudad' => 'Poza Rica, Veracruz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Alternativa TV Veracruz',
                'url' => 'https://www.alternativatvveracruz.com',
                'ciudad' => 'Tuxpan, Veracruz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'El Heraldo de Poza Rica',
                'url' => 'https://elheraldodepozarica.com.mx',
                'ciudad' => 'Poza Rica, Veracruz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Enteratever',
                'url' => 'https://www.enteratever.com/2023/',
                'ciudad' => 'Tihuatlan, Veracruz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Imagen del Norte',
                'url' => 'www.imagendelnorte.com',
                'ciudad' => 'Tuxpan, Veracriz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Nexo Veracruz',
                'url' => 'https://nexoveracruz.com/',
                'ciudad' => 'Poza Rica, Veracriz',
                'tipo' => 'Portal',
            ],
            [
                'nombre' => 'Elementos MX (Red Social)',
                'url' => '',
                'ciudad' => 'Tuxpan, Veracruz',
                'tipo' => 'Red social',
            ],
            [
                'nombre' => 'Noreste (Red Social)',
                'url' => '',
                'ciudad' => 'Poza Rica, Veracruz',
                'tipo' => 'Red social',
            ],
            [
                'nombre' => 'Imagen del Norte (Red Social)',
                'url' => '',
                'ciudad' => 'Tuxpan, Veracruz',
                'tipo' => 'Red social',
            ],
        ];
        foreach ($portales as $portal) {
            PortalInternet::create($portal);
        }
    }
}
