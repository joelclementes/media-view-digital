<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrensaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('portales_prensa')->insert([
            [
                'id' => 1,
                'nombre' => 'Revista Líder en Política y Negocios',
                'url' => 'www.larevistalider.com',
                'ciudad' => 'Veracruz, Veracruz/',
            ],
            [
                'id' => 2,
                'nombre' => 'Diario de Xalapa',
                'url' => 'https://www.diariodexalapa.com.mx',
                'ciudad' => 'Xalapa, Veracruz/',
            ],
            [
                'id' => 3,
                'nombre' => 'Diario de Cardel',
                'url' => 'http://diariocardel.com.mx',
                'ciudad' => 'La Antigua, Veracruz',
            ],
            [
                'id' => 4,
                'nombre' => 'Diario de Acayucan/',
                'url' => 'http://diarioacayucan.com/',
                'ciudad' => 'Acayucan, Veracruz/',
            ],
            [
                'id' => 5,
                'nombre' => 'El Heraldo de Martínez',
                'url' => 'https://elheraldodemartinez.com.mx/',
                'ciudad' => 'Martínez de la Torre, Veracruz',
            ],
            [
                'id' => 6,
                'nombre' => 'El Heraldo de Tuxpan',
                'url' => 'http://elheraldodetuxpan.com.mx',
                'ciudad' => 'Tuxpan, Veracruz',
            ],
            [
                'id' => 7,
                'nombre' => 'El Heraldo de Poza Rica',
                'url' => 'https://elheraldodepozarica.com.mx/',
                'ciudad' => 'Poza Rica, Veracruz',
            ],
            [
                'id' => 8,
                'nombre' => 'Diario Gráfico de Xalapa',
                'url' => 'www.graficoaldia.mx',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 9,
                'nombre' => 'Diario del Istmo',
                'url' => 'www.diariodelistmo.com',
                'ciudad' => 'Coatzacoalcos, Veracruz',
            ],
            [
                'id' => 10,
                'nombre' => 'El Dictamen',
                'url' => 'http://eldictamen.mx',
                'ciudad' => 'Veracruz, Veracruz',
            ],
            [
                'id' => 11,
                'nombre' => 'Imagen de Veracruz',
                'url' => 'www.imagendeveracruz.mx',
                'ciudad' => 'Boca del Río, Veracruz',
            ],
            [
                'id' => 12,
                'nombre' => 'La Opinión de Poza Rica',
                'url' => 'www.laopinion.net',
                'ciudad' => 'Poza Rica, Veracruz',
            ],
            [
                'id' => 13,
                'nombre' => 'El Liberal del Sur',
                'url' => 'www.liberal.com.mx',
                'ciudad' => 'Coatzacoalcos, Veracruz',
            ],
            [
                'id' => 14,
                'nombre' => 'Diario de los Tuxtlas',
                'url' => 'http://diariolostuxtlas.com',
                'ciudad' => 'San Andrés Tuxtla, Veracruz',
            ],
            [
                'id' => 15,
                'nombre' => 'El Chiltepín',
                'url' => 'http://www.elchiltepin.com',
                'ciudad' => 'Misantla, Veracruz',
            ],
            [
                'id' => 16,
                'nombre' => 'Crónica de Tierra Blanca',
                'url' => 'www.cronicadetierrablanca.com',
                'ciudad' => 'Tierra Blanca, Veracruz',
            ],
            [
                'id' => 17,
                'nombre' => 'Notiver',
                'url' => 'www.notiver.com.mx',
                'ciudad' => 'Veracruz, Veracruz',
            ],
            [
                'id' => 18,
                'nombre' => 'El Sol de Orizaba (Se convirtió en Diario de Xalapa impreso)',
                'url' => 'https://www.elsoldeorizaba.com.mx',
                'ciudad' => 'Orizaba, Veracruz',
            ],
            [
                'id' => 19,
                'nombre' => 'El Sol de Córdoba (Se convirtió en Diario de Xalapa impreso)',
                'url' => 'https://www.elsoldecordoba.com.mx',
                'ciudad' => 'Córdoba, Veracruz',
            ],
            [
                'id' => 20,
                'nombre' => 'Revista Análisis Político',
                'url' => 'http://revistaanalisispolitico.com',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 21,
                'nombre' => 'Revista Fundamentos',
                'url' => 'http://revistafundamentos.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 22,
                'nombre' => 'La Jornada Veracruz',
                'url' => 'www.jornadaveracruz.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 23,
                'nombre' => 'Diario Reforma',
                'url' => 'www.reforma.com',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 24,
                'nombre' => 'Diario El Universal',
                'url' => 'https://www.eluniversal.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 25,
                'nombre' => 'Diario Milenio',
                'url' => 'www.milenio.com',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 26,
                'nombre' => 'El Sol de México',
                'url' => 'www.elsoldemexico.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 27,
                'nombre' => 'Diario La Jornada',
                'url' => 'http://www.jornada.unam.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 28,
                'nombre' => 'El Heraldo de Xalapa',
                'url' => 'http://heraldodexalapa.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 29,
                'nombre' => 'El Heraldo de Coatzacoalcos',
                'url' => 'http://heraldodecoatzacoalcos.com.mx',
                'ciudad' => 'Coatzacoalcos, Veracruz',
            ],
            [
                'id' => 30,
                'nombre' => 'El Heraldo de Veracruz',
                'url' => 'http://elheraldodeveracruz.com.mx',
                'ciudad' => 'Veracruz, Veracruz',
            ],
            [
                'id' => 31,
                'nombre' => 'Diario Notisur',
                'url' => '',
                'ciudad' => 'Coatzacoalcos, Veracruz',
            ],
            [
                'id' => 32,
                'nombre' => 'El Buen Tono',
                'url' => 'www.elbuentono.com.mx',
                'ciudad' => 'Orizaba, Veracruz',
            ],
            [
                'id' => 33,
                'nombre' => 'Diario de las Huastecas',
                'url' => 'www.diariodelashuastecas.com',
                'ciudad' => 'Huejutla, Hidalgo',
            ],
            [
                'id' => 34,
                'nombre' => 'El Piñero de la Cuenca',
                'url' => 'https://www.elpinero.mx',
                'ciudad' => 'Loma Bonita, Oaxaca',
            ],
            [
                'id' => 35,
                'nombre' => 'Periódico Órale! Jarocho',
                'url' => '',
                'ciudad' => 'Boca del Río, Veracruz',
            ],
            [
                'id' => 36,
                'nombre' => 'ÓRALE! Las Noticias en Caliente',
                'url' => '',
                'ciudad' => 'Coatzacoalcos, Veracruz',
            ],
            [
                'id' => 37,
                'nombre' => 'Vanguardia',
                'url' => 'https://vanguardiaveracruz.com',
                'ciudad' => 'Poza Rica, Veracruz',
            ],
            [
                'id' => 38,
                'nombre' => 'Excelsior',
                'url' => 'https://www.excelsior.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 39,
                'nombre' => 'El Economista',
                'url' => 'https://www.eleconomista.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 40,
                'nombre' => 'Crónica de Xalapa Impreso',
                'url' => 'www.cronicadexalapa.com.mx',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 41,
                'nombre' => 'Aregional',
                'url' => '',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 42,
                'nombre' => 'El Diario de Minatitlán',
                'url' => 'www.eldiariodeminatitlan.com.mx',
                'ciudad' => 'Minatitlán, Veracruz',
            ],
            [
                'id' => 43,
                'nombre' => 'El Chamuco y los Hijos del Averno',
                'url' => 'https://elchamuco.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 44,
                'nombre' => 'Publimetro',
                'url' => 'www.publimetro.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 45,
                'nombre' => 'Periódico la Razón',
                'url' => 'www.razon.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 46,
                'nombre' => 'Revista Llave Negocios & Política',
                'url' => '',
                'ciudad' => 'Boca del Río, Veracruz',
            ],
            [
                'id' => 47,
                'nombre' => 'Diario Eyipantla',
                'url' => '',
                'ciudad' => '',
            ],
            [
                'id' => 48,
                'nombre' => 'Revista innova digital',
                'url' => '',
                'ciudad' => '',
            ],
            [
                'id' => 49,
                'nombre' => 'Periódico Órale! Xalapa',
                'url' => '',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 50,
                'nombre' => 'El Sol de Tampico',
                'url' => '',
                'ciudad' => 'Tampico',
            ],
            [
                'id' => 51,
                'nombre' => 'NV Periodismo de Investigación',
                'url' => 'www.nvinvestigacion.com',
                'ciudad' => 'Xalapa, Veracruz',
            ],
            [
                'id' => 52,
                'nombre' => 'Record',
                'url' => 'www.record.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 53,
                'nombre' => 'Revista TV Notas',
                'url' => 'www.tvnotas.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 54,
                'nombre' => 'Pásala',
                'url' => 'www.pasala.com.mx',
                'ciudad' => 'CDMX',
            ],
            [
                'id' => 55,
                'nombre' => 'Publimetro Querétaro',
                'url' => 'www.publimetro.com.mx',
                'ciudad' => 'Querétaro',
            ],
            [
                'id' => 56,
                'nombre' => 'Crónica Veracruz',
                'url' => 'www.cronicaveracruz.com',
                'ciudad' => 'Veracruz, Veracruz',
            ],
            [
                'id' => 57,
                'nombre' => 'Maya Politic Sureste',
                'url' => 'https://mayapoliticsureste.com',
                'ciudad' => 'Mérida, Yucatán',
            ],
            [
                'id' => 58,
                'nombre' => 'Revista El Heraldo Veracruz',
                'url' => '',
                'ciudad' => 'Veracruz, Veracruz',
            ],
            [
                'id' => 59,
                'nombre' => 'Semanario Mensaje del Sureste',
                'url' => 'https://www.elmensajedelsureste.com',
                'ciudad' => 'Minatitlan, Veracruz',
            ]
        ]);
    }
}
