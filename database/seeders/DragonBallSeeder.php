<?php

namespace Database\Seeders;

use App\Models\Juego;
use App\Models\PersonajeDragonBall;
use App\Models\TecnicaDragonBall;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DragonBallSeeder extends Seeder
{
    public function run(): void
    {
        $juego = Juego::query()
            ->where('nombre', 'Dragon Ball Z: Budokai Tenkaichi 3')
            ->firstOrFail();

        /*
         * nombre, personaje base, transformación, categoría/saga, raza,
         * alineación, estilo, icono, ilustración, retrato, destacado
         */
        $catalogo = [
            ['Goku', 'Goku', null, 'Guerreros Z', 'Saiyan', 'Héroe', 'Equilibrado', 'Goku.png', '01 Goku.png', 'Goku 01.png', true],
            ['Goku Super Saiyan', 'Goku', 'Super Saiyan', 'Guerreros Z', 'Saiyan', 'Héroe', 'Ataque', 'Goku SS.png', '02 Goku SS.png', 'Goku 02 - Super Saiyan.png', true],
            ['Goku Super Saiyan 2', 'Goku', 'Super Saiyan 2', 'Guerreros Z', 'Saiyan', 'Héroe', 'Velocidad', 'Goku SS2.png', '03 Goku SS2.png', 'Goku 03 - Super Saiyan 2.png', false],
            ['Goku Super Saiyan 3', 'Goku', 'Super Saiyan 3', 'Saga Majin Buu', 'Saiyan', 'Héroe', 'Poder', 'Goku SS3.png', '04 Goku SS3.png', 'Goku 04 - Super Saiyan 3.png', true],
            ['Goku Super Saiyan 4', 'Goku', 'Super Saiyan 4', 'Dragon Ball GT', 'Saiyan', 'Héroe', 'Poder', 'Goku SS4.png', '05 Goku SS4.png', 'Goku 05 - Super Saiyan 4.png', true],
            ['Vegeta', 'Vegeta', null, 'Guerreros Z', 'Saiyan', 'Héroe', 'Ataque', 'Vegeta 01.png', '06 Vegeta.png', 'Vegeta 01.png', true],
            ['Vegeta Super Saiyan', 'Vegeta', 'Super Saiyan', 'Guerreros Z', 'Saiyan', 'Héroe', 'Ataque', 'Vegeta 02.png', '07 Vegeta SS.png', 'Vegeta 02 - Super Saiyan.png', false],
            ['Super Vegeta', 'Vegeta', 'Super Saiyan grado 2', 'Saga Cell', 'Saiyan', 'Héroe', 'Poder', 'Vegeta 03.png', '08 Super Vegeta SS.png', 'Vegeta 03 - Super Vegeta Super Saiyan.png', false],
            ['Majin Vegeta', 'Vegeta', 'Majin', 'Saga Majin Buu', 'Saiyan', 'Rival', 'Ataque', 'Vegeta 04.png', '09 Majin Vegeta.png', 'Vegeta 04 - Majin.png', true],
            ['Vegeta Super Saiyan 2', 'Vegeta', 'Super Saiyan 2', 'Saga Majin Buu', 'Saiyan', 'Héroe', 'Velocidad', 'Vegeta 05.png', '10 Vegeta SS2.png', 'Vegeta 05 - Super Saiyan 2.png', false],
            ['Vegeta Super Saiyan 4', 'Vegeta', 'Super Saiyan 4', 'Dragon Ball GT', 'Saiyan', 'Héroe', 'Poder', 'Vegeta 06.png', '11 Vegeta SS4.png', 'Vegeta 06 - Super Saiyan 4.png', true],
            ['Vegeta con rastreador', 'Vegeta', 'Armadura Saiyan', 'Saga Saiyan', 'Saiyan', 'Villano', 'Ataque', 'Vegeta (Scouter) 01.png', '12 Vegeta (scouter).png', 'Vegeta (Scouter) 01.png', false],
            ['Gran Mono Vegeta', 'Vegeta', 'Ozaru', 'Saga Saiyan', 'Saiyan', 'Villano', 'Gigante', 'Vegeta (Scouter) 02.png', '13 Vegeta (Great Ape).png', 'Vegeta (Scouter) 02 - Great Ape Vegeta.png', true],
            ['Piccolo', 'Piccolo', null, 'Guerreros Z', 'Namekiano', 'Héroe', 'Técnico', 'Piccolo.png', '14 Piccolo.png', 'Piccolo.png', true],
            ['Krilin', 'Krilin', null, 'Guerreros Z', 'Humano', 'Héroe', 'Velocidad', 'Krillin.png', '15 Krillin.png', 'Krillin.png', false],
            ['Yamcha', 'Yamcha', null, 'Guerreros Z', 'Humano', 'Héroe', 'Velocidad', 'Yamcha.png', '16 Yamcha.png', 'Yamcha.png', false],
            ['Tenshinhan', 'Tenshinhan', null, 'Guerreros Z', 'Humano', 'Héroe', 'Técnico', 'Tien.png', '17 Tien.png', 'Tien.png', false],
            ['Chaoz', 'Chaoz', null, 'Guerreros Z', 'Humano', 'Héroe', 'Técnico', 'Chiaotzu.png', '18 Chiaotzu.png', 'Chiaotzu.png', false],
            ['Gohan niño', 'Gohan', 'Niño', 'Saga Saiyan', 'Híbrido Saiyan', 'Héroe', 'Equilibrado', 'Kid Gohan.png', '19 Kid Gohan.png', 'Kid Gohan.png', false],
            ['Gohan adolescente', 'Gohan', 'Adolescente', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Equilibrado', 'Teen Gohan 01.png', '20 Teen Gohan.png', 'Teen Gohan 01.png', false],
            ['Gohan adolescente Super Saiyan', 'Gohan', 'Adolescente Super Saiyan', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Ataque', 'Teen Gohan 02.png', '21 Teen Gohan SS.png', 'Teen Gohan 02 Super Saiyan.png', false],
            ['Gohan adolescente Super Saiyan 2', 'Gohan', 'Adolescente Super Saiyan 2', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Poder', 'Teen Gohan 03.png', '22 Teen Gohan SS2.png', 'Teen Gohan 03 Super Saiyan 2.png', true],
            ['Gohan adulto', 'Gohan', 'Adulto', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Equilibrado', 'Gohan 01.png', '23 Gohan.png', 'Gohan 01.png', false],
            ['Gohan adulto Super Saiyan', 'Gohan', 'Adulto Super Saiyan', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Ataque', 'Gohan 02.png', '24 Gohan SS.png', 'Gohan 02 Super Saiyan.png', false],
            ['Gohan adulto Super Saiyan 2', 'Gohan', 'Adulto Super Saiyan 2', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Ataque', 'Gohan 03.png', '25 Gohan SS2.png', 'Gohan 03 Super Saiyan 2.png', false],
            ['Gohan definitivo', 'Gohan', 'Potencial desbloqueado', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Poder', 'Gohan 04.png', '26 Ultimate Gohan.png', 'Gohan 04 Ultimate.png', true],
            ['Gran Saiyaman', 'Gohan', 'Gran Saiyaman', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Técnico', 'Great Saiyaman.png', '27 Great Saiyaman.png', 'Great Saiyaman.png', false],
            ['Trunks del futuro', 'Trunks del futuro', 'Espada', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Técnico', 'Trunks (w Sword) 01.png', '28 Trunks (sword).png', 'Trunks (w Sword) 01.png', true],
            ['Trunks del futuro Super Saiyan', 'Trunks del futuro', 'Espada Super Saiyan', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Ataque', 'Trunks (w Sword) 02.png', '29 Trunks (sword) SS.png', 'Trunks (w Sword) 02 - Super Saiyan.png', false],
            ['Trunks luchador', 'Trunks del futuro', 'Armadura Saiyan', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Equilibrado', 'Trunks (Fighting) 01.png', '30 Trunks (fighting).png', 'Trunks (Fighting) 01.png', false],
            ['Trunks luchador Super Saiyan', 'Trunks del futuro', 'Armadura Super Saiyan', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Ataque', 'Trunks (Fighting) 02.png', '31 Trunks (fighting) SS.png', 'Trunks (Fighting) 02 - Super Saiyan.png', false],
            ['Super Trunks', 'Trunks del futuro', 'Super Saiyan grado 3', 'Saga Cell', 'Híbrido Saiyan', 'Héroe', 'Poder', 'Trunks (Fighting) 03.png', '32 Super Trunks.png', 'Trunks (Fighting) 03 - Super Trunks Super Saiyan.png', false],
            ['Trunks niño', 'Trunks niño', null, 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Velocidad', 'Kid Trunks 01.png', '33 Kid Trunks.png', 'Kid Trunks 01.png', false],
            ['Trunks niño Super Saiyan', 'Trunks niño', 'Super Saiyan', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Velocidad', 'Kid Trunks 02.png', '34 Kid Trunks SS.png', 'Kid Trunks 02 - Super Saiyan.png', false],
            ['Goten', 'Goten', null, 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Velocidad', 'Goten 01.png', '35 Goten.png', 'Goten 01.png', false],
            ['Goten Super Saiyan', 'Goten', 'Super Saiyan', 'Saga Majin Buu', 'Híbrido Saiyan', 'Héroe', 'Velocidad', 'Goten 02.png', '36 Goten SS.png', 'Goten 02 - Super Saiyan.png', false],
            ['Gotenks', 'Gotenks', 'Fusión', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Técnico', 'Gotenks 01.png', '37 Gotenks.png', 'Gotenks 01.png', false],
            ['Gotenks Super Saiyan', 'Gotenks', 'Super Saiyan', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Ataque', 'Gotenks 02.png', '38 Gotenks SS.png', 'Gotenks 02 - Super Saiyan.png', false],
            ['Gotenks Super Saiyan 3', 'Gotenks', 'Super Saiyan 3', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Poder', 'Gotenks 03.png', '39 Gotenks SS3.png', 'Gotenks 03 - Super Saiyan 3.png', true],
            ['Vegetto', 'Vegetto', 'Fusión Potara', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Equilibrado', 'Vegito 01.png', '40 Vegito.png', 'Vegito 01.png', false],
            ['Super Vegetto', 'Vegetto', 'Super Saiyan', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Poder', 'Vegito 02.png', '41 Vegito SS.png', 'Vegito 02 - Super Vegito Super Saiyan.png', true],
            ['Gogeta Super Saiyan', 'Gogeta', 'Super Saiyan', 'Fusiones', 'Fusión Saiyan', 'Héroe', 'Poder', 'Gogeta.png', '42 Gogeta SS.png', 'Gogeta - Super Saiyan.png', true],
            ['Gogeta Super Saiyan 4', 'Gogeta', 'Super Saiyan 4', 'Dragon Ball GT', 'Fusión Saiyan', 'Héroe', 'Poder', 'Super Gogeta.png', '43 Super Gogeta.png', 'Super Gogeta - Super Saiyan 4.png', true],
            ['Raditz', 'Raditz', null, 'Saga Saiyan', 'Saiyan', 'Villano', 'Ataque', 'Raditz.png', '44 Raditz.png', 'Raditz.png', false],
            ['Nappa', 'Nappa', null, 'Saga Saiyan', 'Saiyan', 'Villano', 'Poder', 'Nappa.png', '45 Nappa.png', 'Nappa.png', false],
            ['Saibaiman', 'Saibaiman', null, 'Saga Saiyan', 'Bio-guerrero', 'Villano', 'Velocidad', 'Saibaman.png', '46 Saibaman.png', 'Saibaman.png', false],
            ['Zarbon', 'Zarbon', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Técnico', 'Zarbon 01.png', '47 Zarbon.png', 'Zarbon 01.png', false],
            ['Zarbon transformado', 'Zarbon', 'Transformado', 'Saga Freezer', 'Extraterrestre', 'Villano', 'Poder', 'Zarbon 02.png', '48 Zarbon (Post-Transformation).png', 'Zarbon 02 - Post-Transformation.png', false],
            ['Dodoria', 'Dodoria', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Poder', 'Dodoria.png', '49 Dodoria.png', 'Dodoria.png', false],
            ['Capitán Ginyu', 'Capitán Ginyu', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Técnico', 'Captain Ginyu.png', '50 Captain Ginyu.png', 'Captain Ginyu.png', false],
            ['Recoome', 'Recoome', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Poder', 'Recoome.png', '51 Recoome.png', 'Recoome.png', false],
            ['Burter', 'Burter', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Velocidad', 'Burter.png', '52 Burter.png', 'Burter.png', false],
            ['Jeice', 'Jeice', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Ataque', 'Jeice.png', '53 Jeice.png', 'Jeice.png', false],
            ['Guldo', 'Guldo', null, 'Saga Freezer', 'Extraterrestre', 'Villano', 'Técnico', 'Guldo.png', '54 Guldo.png', 'Guldo.png', false],
            ['Freezer primera forma', 'Freezer', 'Primera forma', 'Saga Freezer', 'Raza de Freezer', 'Villano', 'Técnico', 'Frieza 01.png', '55 Frieza (1st form).png', 'Frieza 01 - 1st Form.png', false],
            ['Freezer segunda forma', 'Freezer', 'Segunda forma', 'Saga Freezer', 'Raza de Freezer', 'Villano', 'Poder', 'Frieza 02.png', '56 (Frieza (2nd form).png', 'Frieza 02 - 2nd Form.png', false],
            ['Freezer tercera forma', 'Freezer', 'Tercera forma', 'Saga Freezer', 'Raza de Freezer', 'Villano', 'Velocidad', 'Frieza 03.png', '57 Frieza (3rd form).png', 'Frieza 03 - 3rd Form.png', false],
            ['Freezer forma final', 'Freezer', 'Forma final', 'Saga Freezer', 'Raza de Freezer', 'Villano', 'Equilibrado', 'Frieza 04.png', '58 Frieza (final form).png', 'Frieza 04 - Final Form.png', true],
            ['Freezer poder máximo', 'Freezer', 'Forma final al máximo', 'Saga Freezer', 'Raza de Freezer', 'Villano', 'Poder', 'Frieza 05.png', '59 Frieza (final form Full Power).png', 'Frieza 05 - Final Form Full Power.png', false],
            ['Mecha Freezer', 'Freezer', 'Reconstruido', 'Saga Cell', 'Raza de Freezer', 'Villano', 'Ataque', 'Frieza 06.png', '60 Mecha Frieza.png', 'Frieza 06 - Mecha Frieza.png', false],
            ['Androide 16', 'Androide 16', null, 'Saga Cell', 'Androide', 'Héroe', 'Poder', 'Android 16.png', '61 Android 16.png', 'Android 16.png', false],
            ['Androide 17', 'Androide 17', null, 'Saga Cell', 'Androide', 'Rival', 'Equilibrado', 'Android 17.png', '62 Android 17.png', 'Android 17.png', false],
            ['Androide 18', 'Androide 18', null, 'Saga Cell', 'Androide', 'Rival', 'Velocidad', 'Android 18.png', '63 Android 18.png', 'Android 18.png', true],
            ['Androide 19', 'Androide 19', null, 'Saga Cell', 'Androide', 'Villano', 'Técnico', 'Android 19.png', '64 Android 19.png', 'Android 19.png', false],
            ['Dr. Gero', 'Dr. Gero', 'Androide 20', 'Saga Cell', 'Androide', 'Villano', 'Técnico', 'Android 20.png', '65 Android 20.png', 'Android 20.png', false],
            ['Cell primera forma', 'Cell', 'Primera forma', 'Saga Cell', 'Bio-androide', 'Villano', 'Técnico', 'Cell 01.png', '66 Cell (1st form).png', 'Cell 01 - 1st Form.png', false],
            ['Cell segunda forma', 'Cell', 'Segunda forma', 'Saga Cell', 'Bio-androide', 'Villano', 'Poder', 'Cell 02.png', '67 Cell (2nd form).png', 'Cell 02 - 2nd Form.png', false],
            ['Cell perfecto', 'Cell', 'Forma perfecta', 'Saga Cell', 'Bio-androide', 'Villano', 'Equilibrado', 'Cell 03.png', '68 Cell (perfect form).png', 'Cell 03 - Perfect Form.png', true],
            ['Cell perfecto poder máximo', 'Cell', 'Forma perfecta al máximo', 'Saga Cell', 'Bio-androide', 'Villano', 'Poder', 'Cell 04.png', '69 Perfect Cell (Perfect Form).png', 'Cell 04 - Perfect Cell Perfect Form.png', false],
            ['Cell Jr.', 'Cell Jr.', null, 'Saga Cell', 'Bio-androide', 'Villano', 'Velocidad', 'Cell Jr.png', '70 Cell Jr.png', 'Cell Jr.png', false],
            ['Dabura', 'Dabura', null, 'Saga Majin Buu', 'Demonio', 'Villano', 'Técnico', 'Demon King Dabura.png', '71 Demon King Dabura.png', 'Demon King Dabura.png', false],
            ['Majin Buu bueno', 'Majin Buu', 'Buu bueno', 'Saga Majin Buu', 'Majin', 'Héroe', 'Técnico', 'Majin Buu (good).png', '72 Majin Buu (good).png', 'Majin Buu (good).png', true],
            ['Majin Buu maldad pura', 'Majin Buu', 'Maldad pura', 'Saga Majin Buu', 'Majin', 'Villano', 'Velocidad', 'Majin Buu (pure evil).png', '73 Majin Buu (Pure Evil).png', 'Majin Buu (pure evil).png', false],
            ['Super Buu', 'Majin Buu', 'Super Buu', 'Saga Majin Buu', 'Majin', 'Villano', 'Poder', 'Super Buu 01.png', '74 Super Buu.png', 'Super Buu 01.png', false],
            ['Super Buu con Gotenks absorbido', 'Majin Buu', 'Gotenks absorbido', 'Saga Majin Buu', 'Majin', 'Villano', 'Técnico', 'Super Buu 02.png', '75 Majin Buu (Gotenks Absorbed).png', 'Super Buu 02 - Majin Buu Gotenks Absorbed.png', false],
            ['Super Buu con Gohan absorbido', 'Majin Buu', 'Gohan definitivo absorbido', 'Saga Majin Buu', 'Majin', 'Villano', 'Poder', 'Super Buu 03.png', '76 Majin Buu (Ultimate Gohan Absorbed).png', 'Super Buu 03 - Majin Buu Ultimate Gohan Absorbed.png', true],
            ['Kid Buu', 'Majin Buu', 'Buu original', 'Saga Majin Buu', 'Majin', 'Villano', 'Velocidad', 'Kid Buu.png', '77 Kid Buu.png', 'Kid Buu.png', true],
            ['Mr. Satán', 'Mr. Satán', null, 'Saga Cell', 'Humano', 'Héroe', 'Técnico', 'Hercule.png', '78 Hercule.png', 'Hercule.png', false],
            ['Videl', 'Videl', null, 'Saga Majin Buu', 'Humana', 'Héroe', 'Velocidad', 'Videl.png', '79 Videl.png', 'Videl.png', false],
            ['Bardock', 'Bardock', null, 'Películas y especiales', 'Saiyan', 'Héroe', 'Ataque', 'Bardock.png', '80 Bardock.png', 'Bardock.png', true],
            ['Cooler forma final', 'Cooler', 'Forma final', 'Películas y especiales', 'Raza de Freezer', 'Villano', 'Poder', 'Cooler 01.png', '81 Cooler (final form).png', 'Cooler 01 - Final Form.png', false],
            ['Broly', 'Broly', 'Super Saiyan legendario', 'Películas y especiales', 'Saiyan', 'Villano', 'Poder', 'Broly.png', '82 Broly.png', 'Broly.png', true],
            ['Bojack transformado', 'Bojack', 'Transformado', 'Películas y especiales', 'Extraterrestre', 'Villano', 'Poder', 'Bojack 01.png', '83 Bojack (Post-Transformation).png', 'Bojack 01 - Post-Transformation.png', false],
            ['Janemba', 'Janemba', 'Super Janemba', 'Películas y especiales', 'Demonio', 'Villano', 'Técnico', 'Janemba.png', '84 Janemba.png', 'Janemba.png', true],
            ['Baby Vegeta', 'Baby Vegeta', null, 'Dragon Ball GT', 'Tsufur', 'Villano', 'Técnico', 'Baby Vegeta.png', '85 Baby Vegeta.png', 'Baby Vegeta.png', false],
            ['Super 17', 'Super 17', 'Fusión androide', 'Dragon Ball GT', 'Androide', 'Villano', 'Técnico', 'Super 17.png', '86 Super 17.png', 'Super 17.png', false],
            ['Gran Mono', 'Gran Mono', 'Ozaru', 'Dragon Ball clásico', 'Saiyan', 'Villano', 'Gigante', 'Great Ape.png', '87 Great Ape.png', 'Great Ape.png', false],
            ['Goku niño', 'Goku niño', null, 'Dragon Ball clásico', 'Saiyan', 'Héroe', 'Velocidad', 'Kid Goku.png', '88 Kid Goku.png', 'Kid Goku.png', true],
            ['Maestro Roshi', 'Maestro Roshi', null, 'Dragon Ball clásico', 'Humano', 'Héroe', 'Técnico', 'Master Roshi.png', '89 Master Roshi.png', 'Master Roshi.png', false],
            ['Tao Pai Pai', 'Tao Pai Pai', null, 'Dragon Ball clásico', 'Humano', 'Villano', 'Técnico', 'General Tao.png', '90 General Tao.png', 'General Tao.png', false],
        ];

        foreach ($catalogo as $indice => $datos) {
            [
                $nombre,
                $personajeBase,
                $transformacion,
                $saga,
                $raza,
                $alineacion,
                $estilo,
                $icono,
                $ilustracion,
                $retrato,
                $destacado,
            ] = $datos;

            $personaje = PersonajeDragonBall::updateOrCreate(
                [
                    'juego_id' => $juego->id,
                    'slug' => Str::slug($nombre),
                ],
                [
                    'nombre' => $nombre,
                    'personaje_base' => $personajeBase,
                    'transformacion' => $transformacion,
                    'saga' => $saga,
                    'raza' => $raza,
                    'alineacion' => $alineacion,
                    'estilo_combate' => $estilo,
                    'descripcion' => $this->descripcion(
                        $nombre,
                        $transformacion,
                        $saga,
                        $estilo
                    ),
                    'desbloqueo' => 'Consulta las condiciones del modo Historia, '
                        .'los torneos y la tienda del juego para confirmar su '
                        .'método de desbloqueo en tu versión.',
                    'icono' => '/imagenes/dragon-ball/budokai-tenkaichi-3/iconos/'.$icono,
                    'ilustracion' => '/imagenes/dragon-ball/budokai-tenkaichi-3/ilustraciones/'.$ilustracion,
                    'retrato' => '/imagenes/dragon-ball/budokai-tenkaichi-3/retratos/'.$retrato,
                    'orden' => $indice + 1,
                    'destacado' => $destacado,
                    'publicado' => true,
                ]
            );

            $this->crearTecnicas($personaje);
        }
    }

    private function descripcion(
        string $nombre,
        ?string $transformacion,
        string $saga,
        string $estilo
    ): string {
        $forma = $transformacion
            ? "Esta variante representa la transformación {$transformacion}."
            : 'Esta es la versión base disponible en el plantel.';

        return "{$nombre} forma parte del catálogo de Dragon Ball Z: "
            ."Budokai Tenkaichi 3 y está asociado a {$saga}. {$forma} "
            ."Su perfil de combate se clasifica como {$estilo}.";
    }

    private function crearTecnicas(PersonajeDragonBall $personaje): void
    {
        $tecnicas = [
            'goku' => [
                ['Kamehameha', 'Ataque especial'],
                ['Genkidama', 'Ataque definitivo'],
            ],
            'goku-super-saiyan-3' => [
                ['Super Kamehameha', 'Ataque especial'],
                ['Puño del Dragón', 'Ataque definitivo'],
            ],
            'vegeta' => [
                ['Galick Ho', 'Ataque especial'],
                ['Big Bang Attack', 'Ataque definitivo'],
            ],
            'majin-vegeta' => [
                ['Big Bang Attack', 'Ataque especial'],
                ['Explosión final', 'Ataque definitivo'],
            ],
            'piccolo' => [
                ['Makankosappo', 'Ataque definitivo'],
                ['Granada infernal', 'Ataque especial'],
            ],
            'gohan-adolescente-super-saiyan-2' => [
                ['Kamehameha padre e hijo', 'Ataque definitivo'],
            ],
            'trunks-del-futuro' => [
                ['Ataque ardiente', 'Ataque especial'],
                ['Combo de espada', 'Ataque definitivo'],
            ],
            'freezer-forma-final' => [
                ['Rayo de la muerte', 'Ataque especial'],
                ['Supernova', 'Ataque definitivo'],
            ],
            'cell-perfecto' => [
                ['Kamehameha perfecto', 'Ataque definitivo'],
                ['Barrera de energía', 'Habilidad'],
            ],
            'kid-buu' => [
                ['Rayo de desaparición', 'Ataque especial'],
                ['Explosión planetaria', 'Ataque definitivo'],
            ],
            'broly' => [
                ['Cañón borrador', 'Ataque especial'],
                ['Omega Blaster', 'Ataque definitivo'],
            ],
            'gogeta-super-saiyan-4' => [
                ['Big Bang Kamehameha', 'Ataque definitivo'],
            ],
        ][$personaje->slug] ?? [];

        foreach ($tecnicas as [$nombre, $tipo]) {
            TecnicaDragonBall::updateOrCreate(
                [
                    'personaje_dragon_ball_id' => $personaje->id,
                    'nombre' => $nombre,
                ],
                [
                    'tipo' => $tipo,
                    'descripcion' => 'Técnica característica de esta versión '
                        .'del personaje dentro del combate.',
                ]
            );
        }
    }
}
