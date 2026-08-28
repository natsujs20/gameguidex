<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear las tablas relacionadas con usuarios,
     * recuperación de claves y sesiones.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');

            $table
                ->string('correo')
                ->unique();

            $table
                ->timestamp('correo_verificado_en')
                ->nullable();

            $table->string('clave');

            $table->rememberToken();

            $table->timestamps();
        });

        Schema::create(
            'password_reset_tokens',
            function (Blueprint $table) {
                $table
                    ->string('email')
                    ->primary();

                $table->string('token');

                $table
                    ->timestamp('created_at')
                    ->nullable();
            }
        );

        Schema::create('sessions', function (Blueprint $table) {
            $table
                ->string('id')
                ->primary();

            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained('usuarios')
                ->nullOnDelete();

            $table
                ->string('ip_address', 45)
                ->nullable();

            $table
                ->text('user_agent')
                ->nullable();

            $table->longText('payload');

            $table
                ->integer('last_activity')
                ->index();
        });
    }

    /**
     * Eliminar las tablas en orden inverso.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};