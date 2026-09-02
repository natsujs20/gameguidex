<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'usuarios';

    /**
     * Atributos permitidos para asignación masiva.
     */
    protected $fillable = [
        'nombre',
        'correo',
        'clave',
    ];

    /**
     * Atributos que no deben mostrarse al convertir
     * el usuario en arreglos o JSON.
     */
    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * Conversiones automáticas de atributos.
     *
     * La clave se cifra automáticamente cuando
     * se asigna al modelo.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correo_verificado_en' => 'datetime',
            'clave' => 'hashed',
        ];
    }

    /**
     * Indicarle a Laravel qué atributo contiene
     * la contraseña del usuario.
     */
    public function getAuthPasswordName(): string
    {
        return 'clave';
    }

    /**
     * Correo utilizado para recuperar la contraseña.
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    /**
     * Dirección utilizada por las notificaciones.
     */
    public function routeNotificationForMail(
        mixed $notification = null
    ): string {
        return $this->correo;
    }

    /**
     * Proyectos creados por el usuario.
     */
    public function proyectos(): HasMany
    {
        return $this->hasMany(
            Proyecto::class,
            'created_by'
        );
    }

    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class, 'usuario_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialVisita::class, 'usuario_id');
    }

    /**
     * Videojuegos que el usuario marcó como jugados.
     */
    public function juegosJugados(): BelongsToMany
    {
        return $this->belongsToMany(
            Juego::class,
            'juegos_jugados',
            'usuario_id',
            'juego_id'
        )->withPivot('jugado_en');
    }

    /**
     * Si el usuario ya marcó este juego como jugado.
     */
    public function marcoJugado(Juego $juego): bool
    {
        return $this->juegosJugados()
            ->where('juegos.id', $juego->id)
            ->exists();
    }

    /**
     * Marca o desmarca un juego como jugado. Devuelve true si quedó
     * marcado, false si se quitó. Mismo patrón que alternarFavorito().
     */
    public function alternarJugado(Juego $juego): bool
    {
        if ($this->marcoJugado($juego)) {
            $this->juegosJugados()->detach($juego->id);

            return false;
        }

        $this->juegosJugados()->attach($juego->id, [
            'jugado_en' => now(),
        ]);

        return true;
    }

    /**
     * Si el usuario ya tiene este elemento en favoritos.
     */
    public function tieneFavorito(Model $elemento): bool
    {
        return $this->favoritos()
            ->where('elemento_type', $elemento->getMorphClass())
            ->where('elemento_id', $elemento->getKey())
            ->exists();
    }

    /**
     * Agrega o quita el elemento de favoritos según su estado actual.
     * Devuelve true si quedó marcado como favorito, false si se quitó.
     */
    public function alternarFavorito(Model $elemento): bool
    {
        $favorito = $this->favoritos()
            ->where('elemento_type', $elemento->getMorphClass())
            ->where('elemento_id', $elemento->getKey())
            ->first();

        if ($favorito) {
            $favorito->delete();

            return false;
        }

        $this->favoritos()->create([
            'elemento_type' => $elemento->getMorphClass(),
            'elemento_id' => $elemento->getKey(),
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Registra (o actualiza) la visita a un elemento en el historial.
     * updateOrCreate evita que el historial crezca sin límite cuando el
     * usuario vuelve a ver lo mismo varias veces.
     */
    public function registrarVisita(Model $elemento): void
    {
        $this->historial()->updateOrCreate(
            [
                'elemento_type' => $elemento->getMorphClass(),
                'elemento_id' => $elemento->getKey(),
            ],
            [
                'visitado_en' => now(),
            ]
        );
    }
}