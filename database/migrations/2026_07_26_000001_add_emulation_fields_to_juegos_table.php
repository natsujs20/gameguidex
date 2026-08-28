<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->string('nombre_emulador')
                ->nullable()
                ->after('texto_enlace');

            $table->string('enlace_emulador')
                ->nullable()
                ->after('nombre_emulador');

            $table->string('plataforma_emulada')
                ->nullable()
                ->after('enlace_emulador');
        });
    }

    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_emulador',
                'enlace_emulador',
                'plataforma_emulada',
            ]);
        });
    }
};