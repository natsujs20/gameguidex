<?php

use Database\Seeders\DragonBallSeeder;
use Database\Seeders\JuegoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(JuegoSeeder::class);
    $this->seed(DragonBallSeeder::class);
});

it('muestra el centro de Dragon Ball', function () {
    $this
        ->get(route('guias.dragon-ball'))
        ->assertOk()
        ->assertSee('Dragon Ball')
        ->assertSee('Budokai Tenkaichi 3');
});

it('carga los noventa personajes del catálogo', function () {
    expect(\App\Models\PersonajeDragonBall::count())->toBe(90);

    $this
        ->get(route('dragon-ball.personajes.index'))
        ->assertOk()
        ->assertSee('Archivo de personajes');
});

it('busca personajes y muestra su ficha', function () {
    $personaje = \App\Models\PersonajeDragonBall::query()
        ->where('slug', 'goku-super-saiyan-3')
        ->firstOrFail();

    $this
        ->get(route('dragon-ball.personajes.index', ['buscar' => 'Goku']))
        ->assertOk()
        ->assertSee('Goku Super Saiyan 3');

    $this
        ->get(route('dragon-ball.personajes.show', $personaje))
        ->assertOk()
        ->assertSee('Goku Super Saiyan 3')
        ->assertSee('Puño del Dragón');
});

it('filtra el catálogo por saga', function () {
    $this
        ->get(route('dragon-ball.personajes.index', [
            'saga' => 'Dragon Ball GT',
        ]))
        ->assertOk()
        ->assertSee('Goku Super Saiyan 4')
        ->assertSee('Baby Vegeta');
});
