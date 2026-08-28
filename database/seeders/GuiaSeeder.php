<?php

namespace Database\Seeders;

use App\Models\Guia;
use App\Models\Juego;
use Illuminate\Database\Seeder;

class GuiaSeeder extends Seeder
{
    public function run(): void
    {
        $monsterHunterWorld = Juego::where(
            'nombre',
            'Monster Hunter: World'
        )->first();

        if (!$monsterHunterWorld) {
            $this->command->error(
                'No se encontró Monster Hunter: World en la tabla juegos.'
            );

            return;
        }

        $guias = [
            [
                'juego_id' => $monsterHunterWorld->id,
                'titulo' => 'Cómo conseguir la Gema de Teostra',
                'slug' => 'como-conseguir-gema-de-teostra',
                'tipo' => 'Material',
                'descripcion' => 'La Gema de Teostra es un material raro utilizado para fabricar y mejorar armas y armaduras relacionadas con Teostra.',
                'donde_conseguir' => 'Se obtiene como recompensa al completar misiones de rango alto donde aparezca Teostra. También puede aparecer al romper partes del monstruo o al cortar su cola.',
                'pasos' => "1. Desbloquea las misiones de rango alto.\n2. Selecciona una misión donde aparezca Teostra.\n3. Rompe la cabeza, las alas y corta la cola cuando sea posible.\n4. Captura o derrota a Teostra.\n5. Revisa las recompensas de misión y los materiales obtenidos.",
                'requisitos' => 'Tener acceso al rango alto y haber desbloqueado las misiones de Teostra.',
                'consejos' => 'Usa resistencia al fuego y lleva bayas curativas para eliminar el fuego explosivo. Los vales de la suerte pueden mejorar las recompensas recibidas.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'gema teostra, material teostra, rango alto, monster hunter world, armadura teostra',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
            [
                'juego_id' => $monsterHunterWorld->id,
                'titulo' => 'Consejos para derrotar a Teostra',
                'slug' => 'consejos-para-derrotar-a-teostra',
                'tipo' => 'Jefe',
                'descripcion' => 'Guía básica para sobrevivir a los ataques de fuego y explosiones de Teostra.',
                'donde_conseguir' => 'Teostra aparece en misiones de rango alto y en zonas como el Yermo de Agujas y el Lecho de los Ancianos.',
                'pasos' => "1. Equipa una armadura con resistencia al fuego.\n2. Ataca preferentemente la cabeza y las alas.\n3. Aléjate cuando Teostra acumule una gran cantidad de polvo explosivo.\n4. Utiliza cápsulas flash cuando intente volar.\n5. Guarda objetos curativos para la fase final.",
                'requisitos' => 'Acceso a las misiones de rango alto de Teostra.',
                'consejos' => 'No permanezcas cerca cuando prepare su supernova. Las armas de agua o hielo son buenas alternativas.',
                'plataformas' => 'PlayStation 4, Xbox One y PC',
                'dificultad' => 'Difícil',
                'palabras_clave' => 'teostra, derrotar teostra, supernova, jefe, resistencia fuego',
                'imagen' => null,
                'destacada' => true,
                'publicada' => true,
            ],
        ];

        foreach ($guias as $guia) {
            Guia::updateOrCreate(
                ['slug' => $guia['slug']],
                $guia
            );
        }

        $this->command->info('Guías agregadas correctamente.');
    }
}