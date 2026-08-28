<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ambas tablas usan una relación polimórfica (elemento_type +
     * elemento_id) porque un favorito o una visita pueden apuntar a un
     * juego, un monstruo, una guía o un personaje de Dragon Ball, y no
     * tiene sentido una tabla de favoritos por cada tipo de contenido.
     * El mapeo de "elemento_type" a modelo real vive en el morphMap
     * de AppServiceProvider (guarda claves cortas como "juego" en vez
     * del namespace completo de la clase).
     */
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();

            $table->string('elemento_type');
            $table->unsignedBigInteger('elemento_id');

            $table->timestamp('created_at')->useCurrent();

            $table->unique(
                ['usuario_id', 'elemento_type', 'elemento_id'],
                'favoritos_unico_por_usuario'
            );
        });

        Schema::create('historial', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->cascadeOnDelete();

            $table->string('elemento_type');
            $table->unsignedBigInteger('elemento_id');

            /*
             * Cada visita actualiza esta columna en vez de crear una
             * fila nueva, así el historial no crece sin límite con
             * usuarios que vuelven a ver lo mismo muchas veces.
             */
            $table->timestamp('visitado_en');

            $table->timestamps();

            $table->unique(
                ['usuario_id', 'elemento_type', 'elemento_id'],
                'historial_unico_por_usuario'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial');
        Schema::dropIfExists('favoritos');
    }
};
