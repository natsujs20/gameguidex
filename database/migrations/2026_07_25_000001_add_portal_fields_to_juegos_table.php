<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->string('franquicia')
                ->default('Digimon')
                ->after('nombre')
                ->index();

            $table->string('estado_disponibilidad')
                ->default('Sin información')
                ->after('imagen');

            $table->string('enlace_oficial')
                ->nullable()
                ->after('estado_disponibilidad');

            $table->string('texto_enlace')
                ->nullable()
                ->after('enlace_oficial');
        });
    }

    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->dropIndex(['franquicia']);
            $table->dropColumn([
                'franquicia',
                'estado_disponibilidad',
                'enlace_oficial',
                'texto_enlace',
            ]);
        });
    }
};
