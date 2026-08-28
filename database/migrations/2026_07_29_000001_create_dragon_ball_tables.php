<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personajes_dragon_ball', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('juego_id')
                ->constrained('juegos')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('slug');
            $table->string('personaje_base');
            $table->string('transformacion')->nullable();
            $table->string('saga')->nullable();
            $table->string('raza')->nullable();
            $table->string('alineacion')->nullable();
            $table->string('estilo_combate')->nullable();
            $table->unsignedTinyInteger('puntos_dp')->nullable();

            $table->text('descripcion')->nullable();
            $table->text('desbloqueo')->nullable();

            $table->string('icono')->nullable();
            $table->string('ilustracion')->nullable();
            $table->string('retrato')->nullable();

            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('destacado')->default(false);
            $table->boolean('publicado')->default(true);
            $table->timestamps();

            $table->unique(
                ['juego_id', 'slug'],
                'db_personajes_juego_slug_unique'
            );
            $table->index('personaje_base');
            $table->index('saga');
            $table->index('raza');
        });

        Schema::create('tecnicas_dragon_ball', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('personaje_dragon_ball_id')
                ->constrained('personajes_dragon_ball')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('tipo');
            $table->string('comando')->nullable();
            $table->unsignedTinyInteger('coste_ki')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();

            $table->index(
                ['personaje_dragon_ball_id', 'tipo'],
                'db_tecnicas_personaje_tipo_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicas_dragon_ball');
        Schema::dropIfExists('personajes_dragon_ball');
    }
};
