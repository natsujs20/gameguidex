<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('juego_id')
                ->constrained('juegos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('titulo');
            $table->string('slug')->unique();

            $table->enum('tipo', [
                'Objeto',
                'Material',
                'Arma',
                'Armadura',
                'Personaje',
                'Evolución',
                'Misión',
                'Jefe',
                'Consejo',
                'Coleccionable',
                'Otro',
            ])->default('Consejo');

            $table->text('descripcion');
            $table->text('donde_conseguir')->nullable();
            $table->longText('pasos')->nullable();
            $table->text('requisitos')->nullable();
            $table->text('consejos')->nullable();

            $table->string('plataformas')->nullable();
            $table->string('dificultad')->default('Normal');
            $table->string('palabras_clave')->nullable();

            $table->string('imagen')->nullable();
            $table->boolean('destacada')->default(false);
            $table->boolean('publicada')->default(true);

            $table->timestamps();

            $table->index('titulo');
            $table->index('tipo');
            $table->index('destacada');
            $table->index('publicada');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guias');
    }
};