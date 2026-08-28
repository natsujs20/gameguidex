<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar el campo que permite destacar juegos en el catálogo.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('juegos', 'destacado')) {
            Schema::table('juegos', function (Blueprint $table) {
                $table
                    ->boolean('destacado')
                    ->default(false)
                    ->after('imagen');
            });
        }
    }

    /**
     * Eliminar el campo destacado al revertir la migración.
     */
    public function down(): void
    {
        if (Schema::hasColumn('juegos', 'destacado')) {
            Schema::table('juegos', function (Blueprint $table) {
                $table->dropColumn('destacado');
            });
        }
    }
};