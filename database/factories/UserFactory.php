<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Modelo asociado a esta fábrica.
     *
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * Clave reutilizada por los usuarios de prueba.
     */
    protected static ?string $clave;

    /**
     * Definir los datos predeterminados del usuario.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),

            'correo' => fake()
                ->unique()
                ->safeEmail(),

            'correo_verificado_en' => now(),

            'clave' => static::$clave ??= Hash::make(
                'password'
            ),

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Crear un usuario cuyo correo todavía
     * no ha sido verificado.
     */
    public function noVerificado(): static
    {
        return $this->state(
            fn (array $atributos) => [
                'correo_verificado_en' => null,
            ]
        );
    }
}