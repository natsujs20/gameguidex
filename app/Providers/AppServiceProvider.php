<?php

namespace App\Providers;

use App\Models\Guia;
use App\Models\Juego;
use App\Models\Monstruo;
use App\Models\PersonajeDragonBall;
use App\Services\CentrosInformacion;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
         * El servicio guarda en memoria los conteos ya calculados, así
         * que se comparte durante toda la petición: si la portada y el
         * sidebar piden los Centros, las consultas se hacen una sola vez.
         */
        $this->app->singleton(CentrosInformacion::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Claves cortas para favoritos/historial (elemento_type) en vez
         * del namespace completo de la clase. Así la base de datos no
         * queda atada a "App\Models\Juego" literal, y si algún modelo
         * se mueve de carpeta no hay que migrar datos existentes.
         */
        Relation::enforceMorphMap([
            'juego' => Juego::class,
            'monstruo' => Monstruo::class,
            'guia' => Guia::class,
            'personaje_dragon_ball' => PersonajeDragonBall::class,
        ]);

        /*
         * El sidebar se incluye desde el layout, así que no recibe datos
         * de ningún controlador. Este composer le inyecta los Centros de
         * Información disponibles para que la vista no tenga que
         * consultarlos por su cuenta.
         */
        View::composer(
            'partials.sidebar',
            function ($view): void {
                $view->with(
                    'centrosDisponibles',
                    app(CentrosInformacion::class)->disponibles()
                );
            }
        );
    }
}
