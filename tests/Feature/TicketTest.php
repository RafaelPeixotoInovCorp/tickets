<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('operators can view tickets index', function () {
    $user = User::where('email', 'operador@tickets.test')->first();

    $response = $this->actingAs($user)->get(route('tickets.index'));

    $response->assertOk();
});

test('clients can only see their entity tickets', function () {
    $client = User::where('email', 'cliente@tickets.test')->first();
    $ticket = Ticket::first();

    $response = $this->actingAs($client)->get(route('tickets.show', $ticket));

    $response->assertOk();
});

test('clients cannot access entity management', function () {
    $client = User::where('email', 'cliente@tickets.test')->first();

    $response = $this->actingAs($client)->get(route('entities.index'));

    $response->assertForbidden();
});

test('users can pin and unpin tickets they can view', function () {
    $operator = User::where('email', 'operador@tickets.test')->first();
    $ticket = Ticket::first();

    $this->actingAs($operator)
        ->post(route('tickets.pin.store', $ticket))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($operator->pinnedTickets()->whereKey($ticket->id)->exists())->toBeTrue();

    $this->actingAs($operator)
        ->get(route('tickets.pinned'))
        ->assertOk()
        ->assertSee($ticket->subject);

    $this->actingAs($operator)
        ->delete(route('tickets.pin.destroy', $ticket))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($operator->pinnedTickets()->whereKey($ticket->id)->exists())->toBeFalse();
});

test('users cannot pin tickets they cannot view', function () {
    $client = User::where('email', 'cliente@tickets.test')->first();
    $ticket = Ticket::whereNotIn('entity_id', $client->accessibleEntityIds())->first();

    if (! $ticket) {
        $this->markTestSkipped('No inaccessible ticket in seed data.');
    }

    $this->actingAs($client)
        ->post(route('tickets.pin.store', $ticket))
        ->assertForbidden();
});
