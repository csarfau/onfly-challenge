<?php

use App\Models\User;
use Carbon\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

it('an authenticated user must be able create a travel request', function () {
    /** @var User $user */
    $user = User::factory()->createOne();
    $destination = fake()->city();

    actingAs($user, 'api')->postJson(route('travel-request.store'), [
        'requester_name'    => $user->name,
        'destination'       => $destination,
        'departure_date'    => today()->format('Y-m-d'),
        'return_date'       => today()->addDays(10)->format('Y-m-d'),
    ])->assertCreated();

    assertDatabaseHas('travel_requests', [
        'user_id' => $user->id,
        'destination' => $destination
    ]);
});

it('requester name is required', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'))
        ->assertJsonValidationErrors([
            'requester_name' => __('validation.required', ['attribute' => 'requester name'])
        ]);
});

it('requester name must be max of 255 characters', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'), [
        'requester_name' => str_repeat('a', 256)
    ])->assertJsonValidationErrors([
        'requester_name' => __('validation.max.string', ['attribute' => 'requester name', 'max' => 255])
    ]);
});

it('destination is required', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'))
        ->assertJsonValidationErrors([
            'destination' => __('validation.required', ['attribute' => 'destination'])
        ]);
});

it('destination must be max of 255 characters', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'), [
        'destination' => str_repeat('a', 256)
    ])->assertJsonValidationErrors([
        'destination' => __('validation.max.string', ['attribute' => 'destination', 'max' => 255])
    ]);
});

it('departure date is required', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'))
        ->assertJsonValidationErrors([
            'departure_date' => __('validation.required', ['attribute' => 'departure date'])
        ]);
});

it('return date is required', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'))
        ->assertJsonValidationErrors([
            'return_date' => __('validation.required', ['attribute' => 'return date'])
        ]);
});

it('departure date should be a valid date (after or equal today)', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'), [
        'departure_date' => Carbon::yesterday()->format('d-m-Y')
    ])
        ->assertJsonValidationErrors([
            'departure_date' => __('validation.after_or_equal', ['attribute' => 'departure date', 'date' => 'today'])
        ]);
});

it('return date should be a valid date (after or equal departure date)', function () {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user, 'api')->postJson(route('travel-request.store'), [
        'departure_date' => today()->addDays(5)->format('d-m-Y'),
        'return_date' => today()->format('d-m-Y')
    ])
        ->assertJsonValidationErrors([
            'return_date' => __('validation.after_or_equal', ['attribute' => 'return date', 'date' => 'departure date'])
        ]);
});
