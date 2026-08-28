<?php

namespace Database\Seeders;

use App\Models\Juego;
use Illuminate\Database\Seeder;

class EmuladoresJuegosSeeder extends Seeder
{
    public function run(): void
    {
        $juegos = [

            /*
            |--------------------------------------------------------------------------
            | PLAYSTATION 1
            |--------------------------------------------------------------------------
            */

            'Digimon World' => [
                'nombre_emulador' => 'DuckStation',
                'enlace_emulador' => 'https://www.duckstation.org/',
                'plataforma_emulada' => 'PlayStation 1',
            ],

            'Digimon World 2' => [
                'nombre_emulador' => 'DuckStation',
                'enlace_emulador' => 'https://www.duckstation.org/',
                'plataforma_emulada' => 'PlayStation 1',
            ],

            'Digimon World 3' => [
                'nombre_emulador' => 'DuckStation',
                'enlace_emulador' => 'https://www.duckstation.org/',
                'plataforma_emulada' => 'PlayStation 1',
            ],

            'Digimon Rumble Arena' => [
                'nombre_emulador' => 'DuckStation',
                'enlace_emulador' => 'https://www.duckstation.org/',
                'plataforma_emulada' => 'PlayStation 1',
            ],

            /*
            |--------------------------------------------------------------------------
            | PLAYSTATION 2
            |--------------------------------------------------------------------------
            */

            'Digimon World 4' => [
                'nombre_emulador' => 'PCSX2',
                'enlace_emulador' => 'https://pcsx2.net/',
                'plataforma_emulada' => 'PlayStation 2',
            ],

            'Dragon Ball Z: Budokai Tenkaichi 3' => [
                'nombre_emulador' => 'PCSX2',
                'enlace_emulador' => 'https://pcsx2.net/',
                'plataforma_emulada' => 'PlayStation 2',
            ],

            /*
            |--------------------------------------------------------------------------
            | NINTENDO DS
            |--------------------------------------------------------------------------
            */

            'Digimon World DS' => [
                'nombre_emulador' => 'melonDS',
                'enlace_emulador' => 'https://melonds.kuribo64.net/downloads.php',
                'plataforma_emulada' => 'Nintendo DS',
            ],

            'Digimon World Dawn' => [
                'nombre_emulador' => 'melonDS',
                'enlace_emulador' => 'https://melonds.kuribo64.net/downloads.php',
                'plataforma_emulada' => 'Nintendo DS',
            ],

            'Digimon World Dusk' => [
                'nombre_emulador' => 'melonDS',
                'enlace_emulador' => 'https://melonds.kuribo64.net/downloads.php',
                'plataforma_emulada' => 'Nintendo DS',
            ],

            /*
            |--------------------------------------------------------------------------
            | GAME BOY ADVANCE
            |--------------------------------------------------------------------------
            */

            'Pokémon Esmeralda' => [
                'nombre_emulador' => 'mGBA',
                'enlace_emulador' => 'https://mgba.io/downloads.html',
                'plataforma_emulada' => 'Game Boy Advance',
            ],

            'Pokémon Rojo Fuego' => [
                'nombre_emulador' => 'mGBA',
                'enlace_emulador' => 'https://mgba.io/downloads.html',
                'plataforma_emulada' => 'Game Boy Advance',
            ],
        ];

        foreach ($juegos as $nombre => $datos) {
            Juego::where('nombre', $nombre)->update($datos);
        }
    }
}