<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Cargar los datos iniciales de la aplicación.
     *
     * Con este seeder una base de datos vacía (por ejemplo la de
     * Supabase recién migrada) queda con el catálogo completo:
     * videojuegos, monstruos, personajes y guías.
     *
     * Todos los seeders usan updateOrCreate, así que se puede volver
     * a ejecutar sin duplicar información.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'correo' => 'admin@gameguidex.cl',
            ],
            [
                'nombre' => 'Administrador',

                'clave' => Hash::make(
                    '12345678'
                ),
            ]
        );

        /*
         * El orden importa: los catálogos de Monster Hunter, Dragon Ball
         * y las guías buscan su videojuego por nombre, así que los
         * seeders de juegos deben ejecutarse antes.
         */
        $this->call([
            // 1. Catálogo de videojuegos y sus datos complementarios.
            JuegoSeeder::class,
            CatalogoAmpliadoSeeder::class,
            ActualizarInformacionJuegosSeeder::class,
            EmuladoresJuegosSeeder::class,

            // 2. Centros de información (dependen de los juegos).
            MonsterHunterSeeder::class,
            DragonBallSeeder::class,

            // 3. Guías (dependen de los juegos).
            GuiaSeeder::class,
        ]);
    }
}
