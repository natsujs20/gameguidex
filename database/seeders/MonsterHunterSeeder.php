<?php

namespace Database\Seeders;

use App\Models\DebilidadMonstruo;
use App\Models\FuenteMaterial;
use App\Models\Juego;
use App\Models\Material;
use App\Models\Monstruo;
use App\Models\ParteMonstruo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MonsterHunterSeeder extends Seeder
{
    public function run(): void
    {
        $this->crearRathalosWorld();
        $this->crearRathalosRise();
        $this->crearRathalosWilds();
        $this->crearCatalogoBase();

        $this->command->info(
            'Enciclopedia inicial de Monster Hunter cargada correctamente.'
        );
    }

    private function crearRathalosWorld(): void
    {
        $juego = Juego::where(
            'nombre',
            'Monster Hunter: World'
        )->first();

        if (!$juego) {
            $this->command->warn(
                'No se encontró Monster Hunter: World.'
            );

            return;
        }

        $monstruo = $this->crearMonstruo(
            $juego,
            [
                'nombre' => 'Rathalos',
                'slug' => 'rathalos-world',
                'especie' => 'Wyvern volador',
                'elemento' => 'Fuego',
                'estado_alterado' => 'Veneno y fuego',
                'nivel_peligro' => 7,
                'descripcion' => 'Rathalos es conocido como el Rey de los Cielos. Este wyvern volador domina sus territorios desde el aire y combina ataques de fuego con las garras venenosas de sus patas.',
                'habitat' => 'Bosque Primigenio y Lecho de los Ancianos. También puede aparecer en investigaciones, misiones opcionales y eventos.',
                'comportamiento' => 'Pasa gran parte del combate volando. Ataca con bolas de fuego, barridos aéreos, embestidas y golpes con sus garras venenosas.',
                'estrategia' => 'Ataca la cabeza y las alas cuando permanezca en el suelo. Cortar la cola reduce el alcance de sus giros. Lleva resistencia al fuego y antídotos. El daño de dragón y trueno resulta especialmente útil.',
                'imagen' => 'MHW-Rathalos_Icon.png',
                'destacado' => true,
            ]
        );

        $this->crearDebilidades(
            $monstruo,
            [
                ['Elemental', 'Dragón', 3],
                ['Elemental', 'Trueno', 2],
                ['Elemental', 'Agua', 1],
                ['Elemental', 'Hielo', 1],
                ['Estado alterado', 'Aturdimiento', 2],
                ['Estado alterado', 'Sueño', 2],
                ['Estado alterado', 'Parálisis', 2],
                ['Estado alterado', 'Explosión', 1],
            ]
        );

        $this->crearPartes(
            $monstruo,
            [
                [
                    'nombre' => 'Cabeza',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Corte e impacto',
                    'debilidad_corte' => 65,
                    'debilidad_impacto' => 70,
                    'debilidad_disparo' => 60,
                    'recompensa_especial' => 'Romper la cabeza puede entregar caparazones, placas o rubíes.',
                    'consejos' => 'Es uno de sus principales puntos débiles.',
                ],
                [
                    'nombre' => 'Alas',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Disparo',
                    'debilidad_corte' => 50,
                    'debilidad_impacto' => 45,
                    'debilidad_disparo' => 55,
                    'recompensa_especial' => 'Romper las alas facilita obtener membranas y materiales de ala.',
                    'consejos' => 'Dañarlas ayuda a limitar su dominio aéreo.',
                ],
                [
                    'nombre' => 'Cola',
                    'rompible' => false,
                    'cortable' => true,
                    'mejor_tipo_dano' => 'Corte',
                    'debilidad_corte' => 45,
                    'debilidad_impacto' => 40,
                    'debilidad_disparo' => 35,
                    'recompensa_especial' => 'La cola cortada permite realizar un tallado adicional.',
                    'consejos' => 'Cortarla reduce el alcance de sus ataques giratorios.',
                ],
                [
                    'nombre' => 'Lomo',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Impacto',
                    'debilidad_corte' => 25,
                    'debilidad_impacto' => 30,
                    'debilidad_disparo' => 20,
                    'recompensa_especial' => 'Puede entregar médula, placa o rubí.',
                    'consejos' => 'Es más sencillo dañarlo mediante monturas o derribos.',
                ],
            ]
        );

        $materiales = [
            ['Escama de Rathalos+', '7'],
            ['Caparazón de Rathalos', '7'],
            ['Ala de Rathalos', '7'],
            ['Cola de Rathalos', '7'],
            ['Médula de Rathalos', '7'],
            ['Placa de Rathalos', '7'],
            ['Rubí de Rathalos', '7'],
            ['Saco infernal', '7'],
        ];

        $this->crearMateriales(
            $juego,
            $monstruo,
            $materiales,
            [
                ['Escama de Rathalos+', 'Rango alto', 'Tallado', 'Cuerpo', 1, 35],
                ['Caparazón de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 24],
                ['Ala de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 19],
                ['Médula de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 14],
                ['Cola de Rathalos', 'Rango alto', 'Tallado', 'Cola', 1, 70],
                ['Médula de Rathalos', 'Rango alto', 'Tallado', 'Cola', 1, 14],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cola', 1, 2],
                ['Rubí de Rathalos', 'Rango alto', 'Captura', null, 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Parte rota', 'Cabeza', 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Parte rota', 'Lomo', 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Investigación', 'Recompensa de plata', 1, 6],
                ['Rubí de Rathalos', 'Rango alto', 'Investigación', 'Recompensa de oro', 1, 13],
                ['Placa de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 5],
                ['Saco infernal', 'Rango alto', 'Recompensa de misión', null, 1, 15],
            ]
        );
    }

    private function crearRathalosRise(): void
    {
        $juego = Juego::where(
            'nombre',
            'Monster Hunter Rise'
        )->first();

        if (!$juego) {
            $this->command->warn(
                'No se encontró Monster Hunter Rise.'
            );

            return;
        }

        $monstruo = $this->crearMonstruo(
            $juego,
            [
                'nombre' => 'Rathalos',
                'slug' => 'rathalos-rise',
                'especie' => 'Wyvern volador',
                'elemento' => 'Fuego',
                'estado_alterado' => 'Veneno y fuego',
                'nivel_peligro' => 7,
                'descripcion' => 'El Rey de los Cielos regresa en Monster Hunter Rise con ataques aéreos más agresivos, aliento de fuego y poderosas embestidas.',
                'habitat' => 'Ruinas del Santuario, Llanos Arenosos, Bosque Inundado, Cavernas de Lava y Ciudadela.',
                'comportamiento' => 'Combina vuelos constantes con bolas de fuego y ataques venenosos. Puede derribar al cazador desde el aire y cambiar rápidamente de posición.',
                'estrategia' => 'Utiliza cordópteros para recuperarte después de sus ataques. El elemento dragón es muy efectivo. Rompe las alas, ataca la cabeza y corta la cola para aumentar las recompensas.',
                'imagen' => 'MHRise-Rathalos_Icon.png',
                'destacado' => true,
            ]
        );

        $this->crearDebilidades(
            $monstruo,
            [
                ['Elemental', 'Dragón', 3],
                ['Elemental', 'Trueno', 2],
                ['Elemental', 'Agua', 1],
                ['Elemental', 'Hielo', 1],
                ['Estado alterado', 'Aturdimiento', 2],
                ['Estado alterado', 'Sueño', 1],
                ['Estado alterado', 'Parálisis', 1],
                ['Estado alterado', 'Explosión', 1],
            ]
        );

        $this->crearPartes(
            $monstruo,
            [
                [
                    'nombre' => 'Cabeza',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Corte e impacto',
                    'debilidad_corte' => 65,
                    'debilidad_impacto' => 70,
                    'debilidad_disparo' => 60,
                    'recompensa_especial' => 'Romper la cabeza puede entregar una Placa o Rubí de Rathalos.',
                    'consejos' => 'Es su principal punto débil.',
                ],
                [
                    'nombre' => 'Alas',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Disparo',
                    'debilidad_corte' => 50,
                    'debilidad_impacto' => 45,
                    'debilidad_disparo' => 55,
                    'recompensa_especial' => 'Las alas rotas entregan materiales de ala y garra.',
                    'consejos' => 'Aprovecha los derribos para atacarlas.',
                ],
                [
                    'nombre' => 'Cola',
                    'rompible' => false,
                    'cortable' => true,
                    'mejor_tipo_dano' => 'Corte',
                    'debilidad_corte' => 45,
                    'debilidad_impacto' => 40,
                    'debilidad_disparo' => 35,
                    'recompensa_especial' => 'La cola cortada permite un tallado independiente.',
                    'consejos' => 'Puede entregar cola, médula, placa o rubí.',
                ],
            ]
        );

        $materiales = [
            ['Escama de Rathalos', '5'],
            ['Caparazón de Rathalos', '5'],
            ['Membrana de Rathalos', '5'],
            ['Cola de Rathalos', '5'],
            ['Médula de Rathalos', '5'],
            ['Placa de Rathalos', '5'],
            ['Escama de Rathalos+', '7'],
            ['Coraza de Rathalos', '7'],
            ['Ala de Rathalos', '7'],
            ['Rubí de Rathalos', '7'],
        ];

        $this->crearMateriales(
            $juego,
            $monstruo,
            $materiales,
            [
                ['Escama de Rathalos', 'Rango bajo', 'Recompensa de objetivo', null, 1, 16],
                ['Caparazón de Rathalos', 'Rango bajo', 'Recompensa de objetivo', null, 1, 31],
                ['Membrana de Rathalos', 'Rango bajo', 'Recompensa de objetivo', null, 1, 23],
                ['Cola de Rathalos', 'Rango bajo', 'Tallado', 'Cola', 1, 70],
                ['Placa de Rathalos', 'Rango bajo', 'Recompensa de objetivo', null, 1, 2],
                ['Placa de Rathalos', 'Rango bajo', 'Tallado', 'Cuerpo', 1, 1],
                ['Placa de Rathalos', 'Rango bajo', 'Tallado', 'Cola', 1, 3],
                ['Placa de Rathalos', 'Rango bajo', 'Captura', null, 1, 3],
                ['Placa de Rathalos', 'Rango bajo', 'Parte rota', 'Cabeza', 1, 4],
                ['Escama de Rathalos+', 'Rango alto', 'Recompensa de objetivo', null, 1, 15],
                ['Coraza de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 27],
                ['Ala de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 20],
                ['Rubí de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cola', 1, 3],
                ['Rubí de Rathalos', 'Rango alto', 'Captura', null, 1, 3],
                ['Rubí de Rathalos', 'Rango alto', 'Parte rota', 'Cabeza', 1, 4],
                ['Rubí de Rathalos', 'Rango alto', 'Parte rota', 'Lomo', 1, 1],
                ['Rubí de Rathalos', 'Rango alto', 'Objeto caído', null, 1, 1],
            ]
        );
    }

    private function crearRathalosWilds(): void
    {
        $juego = Juego::where(
            'nombre',
            'Monster Hunter Wilds'
        )->first();

        if (!$juego) {
            $this->command->warn(
                'No se encontró Monster Hunter Wilds.'
            );

            return;
        }

        $monstruo = $this->crearMonstruo(
            $juego,
            [
                'nombre' => 'Rathalos',
                'slug' => 'rathalos-wilds',
                'especie' => 'Wyvern volador',
                'elemento' => 'Fuego',
                'estado_alterado' => 'Veneno y fuego',
                'nivel_peligro' => 7,
                'descripcion' => 'Rathalos mantiene su título de Rey de los Cielos en Monster Hunter Wilds. Ataca desde el aire con fuego, garras venenosas y movimientos de gran alcance.',
                'habitat' => 'Puede encontrarse en distintas regiones y misiones de rango alto de Monster Hunter Wilds.',
                'comportamiento' => 'Es extremadamente móvil y utiliza el vuelo para controlar la distancia. Sus ataques generan heridas que el cazador puede destruir para conseguir recompensas adicionales.',
                'estrategia' => 'Crea heridas con ataques concentrados y destrúyelas. Ataca las alas para obtener sus materiales con mayor seguridad y corta la cola para acceder a recompensas exclusivas.',
                'imagen' => 'MHWilds-Rathalos_Icon.png',
                'destacado' => true,
            ]
        );

        $this->crearDebilidades(
            $monstruo,
            [
                ['Elemental', 'Dragón', 3],
                ['Elemental', 'Trueno', 2],
                ['Elemental', 'Agua', 1],
                ['Elemental', 'Hielo', 1],
                ['Estado alterado', 'Aturdimiento', 2],
                ['Estado alterado', 'Parálisis', 1],
                ['Estado alterado', 'Sueño', 1],
            ]
        );

        $this->crearPartes(
            $monstruo,
            [
                [
                    'nombre' => 'Cabeza',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Corte e impacto',
                    'debilidad_corte' => 65,
                    'debilidad_impacto' => 70,
                    'debilidad_disparo' => 60,
                    'recompensa_especial' => 'La cabeza puede producir heridas y entregar materiales adicionales.',
                    'consejos' => 'Atácala durante sus caídas y después de sus bolas de fuego.',
                ],
                [
                    'nombre' => 'Alas',
                    'rompible' => true,
                    'cortable' => false,
                    'mejor_tipo_dano' => 'Disparo',
                    'debilidad_corte' => 50,
                    'debilidad_impacto' => 45,
                    'debilidad_disparo' => 55,
                    'recompensa_especial' => 'Cada ala rota entrega Ala de Rathalos con probabilidad garantizada.',
                    'consejos' => 'Romper ambas alas permite obtener recompensas independientes.',
                ],
                [
                    'nombre' => 'Cola',
                    'rompible' => false,
                    'cortable' => true,
                    'mejor_tipo_dano' => 'Corte',
                    'debilidad_corte' => 45,
                    'debilidad_impacto' => 40,
                    'debilidad_disparo' => 35,
                    'recompensa_especial' => 'La cola cortada tiene su propia tabla de tallado.',
                    'consejos' => 'Es una de las mejores fuentes para conseguir la cola y el rubí.',
                ],
            ]
        );

        $materiales = [
            ['Escama de Rathalos+', '7'],
            ['Coraza de Rathalos', '7'],
            ['Ala de Rathalos', '7'],
            ['Cola de Rathalos', '7'],
            ['Médula de Rathalos', '7'],
            ['Rubí de Rathalos', '7'],
            ['Certificado de Rathalos S', '7'],
            ['Saco infernal', '7'],
        ];

        $this->crearMateriales(
            $juego,
            $monstruo,
            $materiales,
            [
                ['Escama de Rathalos+', 'Rango alto', 'Tallado', 'Cuerpo', 1, 30],
                ['Coraza de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 23],
                ['Ala de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 18],
                ['Cola de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 13],
                ['Médula de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 11],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cuerpo', 1, 5],
                ['Cola de Rathalos', 'Rango alto', 'Tallado', 'Cola cortada', 1, 80],
                ['Médula de Rathalos', 'Rango alto', 'Tallado', 'Cola cortada', 1, 13],
                ['Rubí de Rathalos', 'Rango alto', 'Tallado', 'Cola cortada', 1, 7],
                ['Ala de Rathalos', 'Rango alto', 'Parte rota', 'Ala izquierda', 1, 100],
                ['Ala de Rathalos', 'Rango alto', 'Parte rota', 'Ala derecha', 1, 100],
                ['Escama de Rathalos+', 'Rango alto', 'Herida destruida', null, 1, 50],
                ['Coraza de Rathalos', 'Rango alto', 'Herida destruida', null, 1, 50],
                ['Certificado de Rathalos S', 'Rango alto', 'Recompensa de objetivo', null, 1, 8],
                ['Escama de Rathalos+', 'Rango alto', 'Recompensa de objetivo', null, 1, 20],
                ['Coraza de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 23],
                ['Ala de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 15],
                ['Cola de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 8],
                ['Médula de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 8],
                ['Saco infernal', 'Rango alto', 'Recompensa de objetivo', null, 2, 18],
                ['Rubí de Rathalos', 'Rango alto', 'Recompensa de objetivo', null, 1, 3],
            ]
        );
    }

    /**
     * Crear un catálogo visual amplio.
     *
     * Estas fichas iniciales permiten poblar el buscador y aprovechar la
     * biblioteca de íconos. Los materiales y porcentajes detallados se pueden
     * incorporar progresivamente sin volver a modificar estas fichas.
     */
    private function crearCatalogoBase(): void
    {
        $this->crearMonstruosBase(
            'Monster Hunter: World',
            [
                ['Anjanath', 'anjanath-world', 'Wyvern bruto', 'Fuego', 'Fuego', 6, 'Bosque Primigenio y Yermo de Agujas', 'MHW-Anjanath_Icon.png', true],
                ['Barroth', 'barroth-world', 'Wyvern bruto', 'Agua', 'Plaga de agua', 4, 'Yermo de Agujas', 'MHW-Barroth_Icon.png', false],
                ['Bazelgeuse', 'bazelgeuse-world', 'Wyvern volador', 'Fuego', 'Explosión', 8, 'Diversas regiones de rango alto', 'MHW-Bazelgeuse_Icon.png', true],
                ['Diablos', 'diablos-world', 'Wyvern volador', 'Ninguno', 'Aturdimiento', 7, 'Yermo de Agujas', 'MHW-Diablos_Icon.png', true],
                ['Dodogama', 'dodogama-world', 'Wyvern colmilludo', 'Fuego', 'Explosión', 5, 'Lecho de los Ancianos', 'MHW-Dodogama_Icon.png', false],
                ['Gran Jagras', 'gran-jagras-world', 'Wyvern colmilludo', 'Ninguno', null, 2, 'Bosque Primigenio', 'MHW-Great_Jagras_Icon.png', false],
                ['Jyuratodus', 'jyuratodus-world', 'Wyvern pisciforme', 'Agua', 'Plaga de agua', 4, 'Yermo de Agujas', 'MHW-Jyuratodus_Icon.png', false],
                ['Kulu-Ya-Ku', 'kulu-ya-ku-world', 'Wyvern pájaro', 'Ninguno', 'Aturdimiento', 3, 'Bosque Primigenio y Yermo de Agujas', 'MHW-Kulu-Ya-Ku_Icon.png', false],
                ['Legiana', 'legiana-world', 'Wyvern volador', 'Hielo', 'Plaga de hielo', 6, 'Altiplanos Coralinos', 'MHW-Legiana_Icon.png', true],
                ['Nergigante', 'nergigante-world', 'Dragón anciano', 'Ninguno', 'Sangrado', 9, 'Lecho de los Ancianos', 'MHW-Nergigante_Icon.png', true],
                ['Odogaron', 'odogaron-world', 'Wyvern colmilludo', 'Ninguno', 'Sangrado', 6, 'Valle Putrefacto y Altiplanos Coralinos', 'MHW-Odogaron_Icon.png', true],
                ['Paolumu', 'paolumu-world', 'Wyvern volador', 'Ninguno', 'Presión de viento', 5, 'Altiplanos Coralinos', 'MHW-Paolumu_Icon.png', false],
                ['Pukei-Pukei', 'pukei-pukei-world', 'Wyvern pájaro', 'Ninguno', 'Veneno', 4, 'Bosque Primigenio y Yermo de Agujas', 'MHW-Pukei-Pukei_Icon.png', false],
                ['Rathian', 'rathian-world', 'Wyvern volador', 'Fuego', 'Veneno y fuego', 5, 'Bosque Primigenio y Yermo de Agujas', 'MHW-Rathian_Icon.png', true],
                ['Teostra', 'teostra-world', 'Dragón anciano', 'Fuego', 'Explosión y fuego', 9, 'Yermo de Agujas y Lecho de los Ancianos', 'MHW-Teostra_Icon.png', true],
                ['Tobi-Kadachi', 'tobi-kadachi-world', 'Wyvern colmilludo', 'Trueno', 'Plaga de trueno', 4, 'Bosque Primigenio', 'MHW-Tobi-Kadachi_Icon.png', false],
                ['Vaal Hazak', 'vaal-hazak-world', 'Dragón anciano', 'Dragón', 'Efluvio', 9, 'Valle Putrefacto', 'MHW-Vaal_Hazak_Icon.png', true],
                ['Xeno’jiiva', 'xenojiiva-world', 'Dragón anciano', 'Dragón', 'Fuego y dragón', 10, 'Tierra de la Convergencia', 'MHW-Xenojiiva_Icon.png', true],
            ]
        );

        $this->crearMonstruosBase(
            'Monster Hunter Rise',
            [
                ['Aknosom', 'aknosom-rise', 'Wyvern pájaro', 'Fuego', 'Plaga de fuego', 3, 'Ruinas del Santuario e Islas Heladas', 'MHRise-Aknosom_Icon.png', false],
                ['Almudron', 'almudron-rise', 'Leviatán', 'Agua', 'Plaga de agua', 7, 'Bosque Inundado y Llanos Arenosos', 'MHRise-Almudron_Icon.png', true],
                ['Bishaten', 'bishaten-rise', 'Bestia de colmillos', 'Ninguno', 'Veneno y parálisis', 5, 'Bosque Inundado y Ruinas del Santuario', 'MHRise-Bishaten_Icon.png', false],
                ['Diablos', 'diablos-rise', 'Wyvern volador', 'Ninguno', 'Aturdimiento', 7, 'Llanos Arenosos', 'MHRise-Diablos_Icon.png', true],
                ['Goss Harag', 'goss-harag-rise', 'Bestia de colmillos', 'Hielo', 'Plaga de hielo', 7, 'Islas Heladas', 'MHRise-Goss_Harag_Icon.png', true],
                ['Gran Izuchi', 'gran-izuchi-rise', 'Wyvern pájaro', 'Ninguno', null, 2, 'Ruinas del Santuario e Islas Heladas', 'MHRise-Great_Izuchi_Icon.png', false],
                ['Khezu', 'khezu-rise', 'Wyvern volador', 'Trueno', 'Parálisis y trueno', 4, 'Islas Heladas y Cavernas de Lava', 'MHRise-Khezu_Icon.png', false],
                ['Magnamalo', 'magnamalo-rise', 'Wyvern colmilludo', 'Fuego infernal', 'Plaga infernal', 8, 'Ruinas del Santuario y Cavernas de Lava', 'MHRise-Magnamalo_Icon.png', true],
                ['Mizutsune', 'mizutsune-rise', 'Leviatán', 'Agua', 'Burbuja y agua', 6, 'Ruinas del Santuario y Bosque Inundado', 'MHRise-Mizutsune_Icon.png', true],
                ['Nargacuga', 'nargacuga-rise', 'Wyvern volador', 'Ninguno', 'Sangrado', 6, 'Ruinas del Santuario y Bosque Inundado', 'MHRise-Nargacuga_Icon.png', true],
                ['Rajang', 'rajang-rise', 'Bestia de colmillos', 'Trueno', 'Plaga de trueno', 9, 'Diversas regiones de rango alto', 'MHRise-Rajang_Icon.png', true],
                ['Rakna-Kadaki', 'rakna-kadaki-rise', 'Temnoceran', 'Fuego', 'Plaga de fuego', 7, 'Cavernas de Lava', 'MHRise-Rakna-Kadaki_Icon.png', true],
                ['Rathian', 'rathian-rise', 'Wyvern volador', 'Fuego', 'Veneno y fuego', 5, 'Ruinas del Santuario, Llanos Arenosos y Bosque Inundado', 'MHRise-Rathian_Icon.png', true],
                ['Somnacanth', 'somnacanth-rise', 'Leviatán', 'Agua', 'Sueño', 5, 'Islas Heladas y Bosque Inundado', 'MHRise-Somnacanth_Icon.png', false],
                ['Tetranadon', 'tetranadon-rise', 'Anfibio', 'Agua', 'Plaga de agua', 3, 'Ruinas del Santuario e Islas Heladas', 'MHRise-Tetranadon_Icon.png', false],
                ['Tigrex', 'tigrex-rise', 'Wyvern volador', 'Ninguno', 'Aturdimiento', 7, 'Islas Heladas y Llanos Arenosos', 'MHRise-Tigrex_Icon.png', true],
                ['Zinogre', 'zinogre-rise', 'Wyvern colmilludo', 'Trueno', 'Plaga de trueno', 7, 'Ruinas del Santuario y Bosque Inundado', 'MHRise-Zinogre_Icon.png', true],
                ['Ibushi Serpiente del Viento', 'ibushi-rise', 'Dragón anciano', 'Dragón', 'Plaga de dragón', 9, 'El Baluarte', 'MHRise-Wind_Serpent_Ibushi_Icon.png', true],
            ]
        );

        $this->crearMonstruosBase(
            'Monster Hunter Wilds',
            [
                ['Ajarakan', 'ajarakan-wilds', 'Bestia de colmillos', 'Fuego', 'Plaga de fuego', 7, 'Cuenca Oleosa', 'MHWilds-Ajarakan_Icon.png', true],
                ['Arkveld', 'arkveld-wilds', 'Wyvern volador', 'Dragón', 'Plaga de dragón', 10, 'Diversas regiones de las Tierras Prohibidas', 'MHWilds-Arkveld_Icon.png', true],
                ['Balahara', 'balahara-wilds', 'Leviatán', 'Agua', 'Plaga de agua', 4, 'Llanos Barlovento', 'MHWilds-Balahara_Icon.png', false],
                ['Chatacabra', 'chatacabra-wilds', 'Anfibio', 'Ninguno', 'Aturdimiento', 3, 'Llanos Barlovento', 'MHWilds-Chatacabra_Icon.png', false],
                ['Doshaguma', 'doshaguma-wilds', 'Bestia de colmillos', 'Ninguno', 'Aturdimiento', 5, 'Llanos Barlovento', 'MHWilds-Doshaguma_Icon.png', true],
                ['Gore Magala', 'gore-magala-wilds', 'Desconocido', 'Dragón', 'Frenesí', 9, 'Acantilados Gélidos', 'MHWilds-Gore_Magala_Icon.png', true],
                ['Gravios', 'gravios-wilds', 'Wyvern volador', 'Fuego', 'Plaga de fuego y sueño', 7, 'Cuenca Oleosa', 'MHWilds-Gravios_Icon.png', true],
                ['Hirabami', 'hirabami-wilds', 'Leviatán', 'Hielo', 'Plaga de hielo', 5, 'Acantilados Gélidos', 'MHWilds-Hirabami_Icon.png', false],
                ['Jin Dahaad', 'jin-dahaad-wilds', 'Leviatán', 'Hielo', 'Plaga de hielo', 9, 'Acantilados Gélidos', 'MHWilds-Jin_Dahaad_Icon.png', true],
                ['Lala Barina', 'lala-barina-wilds', 'Temnoceran', 'Ninguno', 'Parálisis', 5, 'Bosque Escarlata', 'MHWilds-Lala_Barina_Icon.png', true],
                ['Nu Udra', 'nu-udra-wilds', 'Molusco', 'Fuego', 'Plaga de fuego', 9, 'Cuenca Oleosa', 'MHWilds-Nu_Udra_Icon.png', true],
                ['Quematrice', 'quematrice-wilds', 'Wyvern bruto', 'Fuego', 'Plaga de fuego', 4, 'Llanos Barlovento', 'MHWilds-Quematrice_Icon.png', false],
                ['Rathian', 'rathian-wilds', 'Wyvern volador', 'Fuego', 'Veneno y fuego', 5, 'Diversas regiones de las Tierras Prohibidas', 'MHWilds-Rathian_Icon.png', true],
                ['Rey Dau', 'rey-dau-wilds', 'Wyvern volador', 'Trueno', 'Plaga de trueno', 9, 'Llanos Barlovento', 'MHWilds-Rey_Dau_Icon.png', true],
                ['Rompopolo', 'rompopolo-wilds', 'Wyvern bruto', 'Ninguno', 'Veneno', 5, 'Cuenca Oleosa', 'MHWilds-Rompopolo_Icon.png', false],
                ['Uth Duna', 'uth-duna-wilds', 'Leviatán', 'Agua', 'Plaga de agua', 9, 'Bosque Escarlata', 'MHWilds-Uth_Duna_Icon.png', true],
                ['Xu Wu', 'xu-wu-wilds', 'Molusco', 'Ninguno', 'Sangrado', 8, 'Ruinas de Wyveria', 'MHWilds-Xu_Wu_Icon.png', true],
                ['Zoh Shia', 'zoh-shia-wilds', 'Dragón anciano', 'Dragón', 'Fuego y dragón', 10, 'Ruinas de Wyveria', 'MHWilds-Zoh_Shia_Icon.png', true],
            ]
        );
    }

    /**
     * Crear fichas iniciales sin eliminar materiales ya registrados.
     */
    private function crearMonstruosBase(
        string $nombreJuego,
        array $monstruos
    ): void {
        $juego = Juego::where('nombre', $nombreJuego)->first();

        if (!$juego) {
            $this->command->warn(
                "No se encontró {$nombreJuego}; se omitió su catálogo."
            );

            return;
        }

        foreach ($monstruos as $datos) {
            [
                $nombre,
                $slug,
                $especie,
                $elemento,
                $estado,
                $peligro,
                $habitat,
                $imagen,
                $destacado,
            ] = $datos;

            Monstruo::updateOrCreate(
                [
                    'juego_id' => $juego->id,
                    'slug' => $slug,
                ],
                [
                    'juego_id' => $juego->id,
                    'nombre' => $nombre,
                    'slug' => $slug,
                    'especie' => $especie,
                    'elemento' => $elemento,
                    'estado_alterado' => $estado,
                    'nivel_peligro' => $peligro,
                    'descripcion' => "{$nombre} es un {$especie} presente en {$nombreJuego}. Esta ficha reúne sus características principales y quedará conectada progresivamente con materiales, recompensas y porcentajes de obtención.",
                    'habitat' => $habitat,
                    'comportamiento' => "Su comportamiento cambia entre estados normales y de furia. Observa sus movimientos, evita atacar sin una apertura y aprovecha los derribos para dañar sus partes más vulnerables.",
                    'estrategia' => "Prepara resistencias adecuadas antes de la misión, estudia sus patrones y prioriza romper partes. Consulta sus debilidades y materiales a medida que la ficha reciba información detallada.",
                    'imagen' => $imagen,
                    'destacado' => $destacado,
                    'publicado' => true,
                ]
            );
        }
    }

    private function crearMonstruo(
        Juego $juego,
        array $datos
    ): Monstruo {
        $monstruo = Monstruo::updateOrCreate(
            [
                'juego_id' => $juego->id,
                'slug' => $datos['slug'],
            ],
            array_merge(
                $datos,
                [
                    'juego_id' => $juego->id,
                    'imagen' => $datos['imagen'] ?? null,
                    'publicado' => true,
                ]
            )
        );

        $monstruo->debilidades()->delete();
        $monstruo->partes()->delete();
        $monstruo->fuentesMateriales()->delete();

        return $monstruo;
    }

    private function crearDebilidades(
        Monstruo $monstruo,
        array $debilidades
    ): void {
        foreach ($debilidades as $debilidad) {
            DebilidadMonstruo::create([
                'monstruo_id' => $monstruo->id,
                'tipo' => $debilidad[0],
                'nombre' => $debilidad[1],
                'intensidad' => $debilidad[2],
                'parte' => $debilidad[3] ?? null,
                'notas' => $debilidad[4] ?? null,
            ]);
        }
    }

    private function crearPartes(
        Monstruo $monstruo,
        array $partes
    ): void {
        foreach ($partes as $parte) {
            ParteMonstruo::create(
                array_merge(
                    $parte,
                    ['monstruo_id' => $monstruo->id]
                )
            );
        }
    }

    private function crearMateriales(
        Juego $juego,
        Monstruo $monstruo,
        array $materiales,
        array $fuentes
    ): void {
        $materialesCreados = [];

        foreach ($materiales as $material) {
            $slug = Str::slug($material[0]);

            $materialesCreados[$material[0]] =
                Material::updateOrCreate(
                    [
                        'juego_id' => $juego->id,
                        'slug' => $slug,
                    ],
                    [
                        'juego_id' => $juego->id,
                        'nombre' => $material[0],
                        'slug' => $slug,
                        'rareza' => $material[1],
                        'descripcion' => 'Material obtenido de '
                            . $monstruo->nombre
                            . ' en '
                            . $juego->nombre
                            . '.',
                        'usos' => 'Se utiliza para fabricar o mejorar armas, armaduras y equipamiento relacionado con Rathalos.',
                        'imagen' => null,
                        'publicado' => true,
                    ]
                );
        }

        foreach ($fuentes as $fuente) {
            $material = $materialesCreados[$fuente[0]];

            FuenteMaterial::create([
                'monstruo_id' => $monstruo->id,
                'material_id' => $material->id,
                'rango' => $fuente[1],
                'metodo' => $fuente[2],
                'parte' => $fuente[3],
                'cantidad' => $fuente[4],
                'porcentaje' => $fuente[5],
                'condicion' => $fuente[6] ?? null,
                'notas' => $fuente[7] ?? null,
            ]);
        }
    }
}
