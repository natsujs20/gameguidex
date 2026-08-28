<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear la tabla de proyectos.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');

            $table
                ->text('descripcion')
                ->nullable();

            $table
                ->foreignId('created_by')
                ->constrained('usuarios')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('nombre');
        });
    }

    /**
     * Eliminar la tabla de proyectos.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};