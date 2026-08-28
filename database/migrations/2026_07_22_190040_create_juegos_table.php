<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('juegos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->unsignedSmallInteger('anio');
            $table->string('plataformas');
            $table->string('genero');
            $table->string('desarrollador');
            $table->text('descripcion');
            $table->string('imagen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('juegos');
    }
};