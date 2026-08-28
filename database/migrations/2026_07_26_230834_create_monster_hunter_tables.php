<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear las tablas de la enciclopedia de Monster Hunter.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Monstruos
        |--------------------------------------------------------------------------
        */

        Schema::create('monstruos', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('juego_id')
                ->constrained('juegos')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('slug');

            $table->string('especie')->nullable();
            $table->string('elemento')->nullable();
            $table->string('estado_alterado')->nullable();
            $table->unsignedTinyInteger('nivel_peligro')->nullable();

            $table->text('descripcion')->nullable();
            $table->text('habitat')->nullable();
            $table->text('comportamiento')->nullable();
            $table->text('estrategia')->nullable();

            $table->string('imagen')->nullable();

            $table->boolean('destacado')->default(false);
            $table->boolean('publicado')->default(true);

            $table->timestamps();

            $table->unique(
                ['juego_id', 'slug'],
                'monstruos_juego_slug_unique'
            );

            $table->index('nombre');
            $table->index('especie');
            $table->index('elemento');
        });

        /*
        |--------------------------------------------------------------------------
        | Materiales
        |--------------------------------------------------------------------------
        */

        Schema::create('materiales', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('juego_id')
                ->constrained('juegos')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('slug');

            $table->string('rareza')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('usos')->nullable();

            $table->string('imagen')->nullable();

            $table->boolean('publicado')->default(true);

            $table->timestamps();

            $table->unique(
                ['juego_id', 'slug'],
                'materiales_juego_slug_unique'
            );

            $table->index('nombre');
            $table->index('rareza');
        });

        /*
        |--------------------------------------------------------------------------
        | Fuentes y porcentajes de obtención
        |--------------------------------------------------------------------------
        |
        | Un mismo material puede tener varios porcentajes dependiendo del
        | rango, método de obtención y parte del monstruo.
        |
        */

        Schema::create('fuentes_materiales', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('monstruo_id')
                ->constrained('monstruos')
                ->cascadeOnDelete();

            $table
                ->foreignId('material_id')
                ->constrained('materiales')
                ->cascadeOnDelete();

            $table->string('rango');

            $table->string('metodo');
            $table->string('parte')->nullable();

            $table->unsignedSmallInteger('cantidad')->default(1);

            $table->decimal(
                'porcentaje',
                5,
                2
            )->nullable();

            $table->text('condicion')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->index(
                ['monstruo_id', 'rango'],
                'fuentes_monstruo_rango_index'
            );

            $table->index(
                ['material_id', 'rango'],
                'fuentes_material_rango_index'
            );

            $table->index('metodo');
        });

        /*
        |--------------------------------------------------------------------------
        | Debilidades
        |--------------------------------------------------------------------------
        |
        | Permite registrar debilidad elemental, estados alterados y otras
        | vulnerabilidades. La intensidad se guarda de 0 a 3 estrellas.
        |
        */

        Schema::create('debilidades_monstruos', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('monstruo_id')
                ->constrained('monstruos')
                ->cascadeOnDelete();

            $table->string('tipo');
            $table->string('nombre');

            $table
                ->unsignedTinyInteger('intensidad')
                ->default(0);

            $table->string('parte')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->index(
                ['monstruo_id', 'tipo'],
                'debilidades_monstruo_tipo_index'
            );

            $table->index('nombre');
        });

        /*
        |--------------------------------------------------------------------------
        | Partes rompibles y cortables
        |--------------------------------------------------------------------------
        */

        Schema::create('partes_monstruos', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('monstruo_id')
                ->constrained('monstruos')
                ->cascadeOnDelete();

            $table->string('nombre');

            $table->boolean('rompible')->default(false);
            $table->boolean('cortable')->default(false);
            $table->boolean('Recompensa')->default(false);
            
            $table->string('mejor_tipo_dano')->nullable();

            $table
                ->unsignedTinyInteger('debilidad_corte')
                ->nullable();

            $table
                ->unsignedTinyInteger('debilidad_impacto')
                ->nullable();

            $table
                ->unsignedTinyInteger('debilidad_disparo')
                ->nullable();

            $table->text('recompensa_especial')->nullable();
            $table->text('consejos')->nullable();

            $table->timestamps();

            $table->index(
                ['monstruo_id', 'nombre'],
                'partes_monstruo_nombre_index'
            );
        });
    }

    /**
     * Eliminar las tablas en orden inverso.
     */
    public function down(): void
    {
        Schema::dropIfExists('partes_monstruos');
        Schema::dropIfExists('debilidades_monstruos');
        Schema::dropIfExists('fuentes_materiales');
        Schema::dropIfExists('materiales');
        Schema::dropIfExists('monstruos');
    }
};