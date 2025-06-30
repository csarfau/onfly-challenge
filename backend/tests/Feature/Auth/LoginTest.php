<?php

use App\Models\User;

use function Pest\Laravel\postJson;

it('email should be required', function () {
    postJson(route('login'))
        ->assertJsonValidationErrors([
            'email' => __('validation.required', ['attribute' => 'email'])
        ]);
});

it('email should have a max of 255 characters', function () {
    postJson(route('login'), [
        'email' => str_repeat('a', 256) . '@email.com'
    ])->assertJsonValidationErrors([
        'email'  =>  __('validation.max.string', ['attribute' => 'email', 'max' => 255])
    ]);
});

it('email should be a valid email', function () {
    postJson(route('login'), [
        'email' => 'invalid-email'
    ])->assertJsonValidationErrors([
        'email' => __('validation.email', ['attribute' => 'email'])
    ]);
});

it('password should be required', function () {
    postJson(route('login'))
        ->assertJsonValidationErrors([
            'password' => __('validation.required', ['attribute' => 'password'])
        ]);
});

it('password should have a max of 255 characters', function () {
    postJson(route('login'), [
        'password' => str_repeat('a', 256)
    ])->assertJsonValidationErrors([
        'password'  =>  __('validation.max.string', ['attribute' => 'password', 'max' => 255])
    ]);
});

it('should be a registered user to login', function () {
    User::factory()->create([
        'email' => 'someone@gmail.com',
        'password' => bcrypt('Test@123')
    ]);

    postJson(route('login'), [
        'email' => 'someone@gmail.com',
        'password' => 'Any@1234'
    ])->assertUnauthorized();

    postJson(route('login'), [
        'email' => 'someone@gmail.com',
        'password' => 'Test@123'
    ])->assertOk();
});

it('should return a jwt token on successful login', function () {
    User::factory()->create([
        'email' => 'someone@gmail.com',
        'password' => bcrypt('Test@123')
    ]);
    postJson(route('login'), [
        'email' => 'someone@gmail.com',
        'password' => 'Test@123'
    ])
        ->assertOK()
        ->assertJson(['token_type' => 'bearer'])
        ->assertJsonPath('expires_in', fn($value) => is_int($value) && $value > 0)
        ->assertJsonPath('access_token', fn($value) => is_string($value) && !empty($value) && count(explode('.', $value)) === 3);
});
