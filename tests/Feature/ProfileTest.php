<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('users can view profile page', function () {
    $user = User::where('email', 'operador@tickets.test')->first();

    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertOk();
});

test('users can update profile name and email', function () {
    $user = User::where('email', 'operador@tickets.test')->first();

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'name' => 'Novo Nome',
        'email' => 'novo@tickets.test',
    ]);

    $response->assertRedirect(route('profile.edit', ['tab' => 'profile']));
    $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Novo Nome', 'email' => 'novo@tickets.test']);
});

test('users can view password tab', function () {
    $user = User::where('email', 'operador@tickets.test')->first();

    $response = $this->actingAs($user)->get(route('profile.edit', ['tab' => 'password']));

    $response->assertOk();
});
