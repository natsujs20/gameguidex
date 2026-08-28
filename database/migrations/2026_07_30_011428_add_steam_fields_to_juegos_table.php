<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('steam_app_id')
                ->nullable()
                ->unique();

            $table
                ->string('steam_url')
                ->nullable();

            $table
                ->boolean('steam_importado')
                ->default(false);

            $table
                ->timestamp('steam_actualizado_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->dropUnique([
                'steam_app_id',
            ]);

            $table->dropColumn([
                'steam_app_id',
                'steam_url',
                'steam_importado',
                'steam_actualizado_at',
            ]);
        });
    }
};