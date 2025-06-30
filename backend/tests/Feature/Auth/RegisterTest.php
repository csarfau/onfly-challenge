<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertTrue;

it('should be able to register as a new user', function () {
    $return = postJson(route('register'), [
        'name'                  => 'Teste Faustino',
        'email'                 => 'teste@email.com',
        'password'              => 'Teste@123',
        'password_confirmation' => 'Teste@123'
    ]);

    $return->assertSuccessful();

    assertDatabaseHas('users', [
        'name'  => 'Teste Faustino',
        'email' => 'teste@email.com'
    ]);

    $user = User::whereEmail('teste@email.com')->firstOrFail();

    assertTrue(
        Hash::check('Teste@123', $user->password)
    );
});

it('name should be required', function () {
    postJson(route('register'), [])
        ->assertJsonValidationErrors([
            'name'  =>  __('validation.required', ['attribute' => 'name'])
        ]);
});

it('name should have a max of 255 characters', function () {
    postJson(route('register'), [
        'name' => str_repeat('a', 256)
    ])->assertJsonValidationErrors([
        'name'  =>  __('validation.max.string', ['attribute' => 'name', 'max' => 255])
    ]);
});

it('email should be required', function () {
    postJson(route('register'), [])
        ->assertJsonValidationErrors([
            'email' => __('validation.required', ['attribute' => 'email'])
        ]);
});

it('email should have a max of 255 characters', function () {
    postJson(route('register'), [
        'email' => str_repeat('a', 256) . '@email.com'
    ])->assertJsonValidationErrors([
        'email'  =>  __('validation.max.string', ['attribute' => 'email', 'max' => 255])
    ]);
});

it('email should be a valid email', function () {
    postJson(route('register'), [
        'email' => 'invalid-email'
    ])->assertJsonValidationErrors([
        'email' => __('validation.email', ['attribute' => 'email'])
    ]);
});

it('email should be unique', function () {
    User::factory()->create([
        'email' => 'teste@email.com'
    ]);

    postJson(route('register'), [
        'email' => 'teste@email.com',
    ])->assertJsonValidationErrors([
        'email' => __('validation.unique', ['attribute' => 'email'])
    ]);
});

it('password should be required', function () {
    postJson(route('register'), [])
        ->assertJsonValidationErrors([
            'password' => __('validation.required', ['attribute' => 'password'])
        ]);
});

it('password should have a max of 255 characters', function () {
    postJson(route('register'), [
        'password' => str_repeat('a', 256)
    ])->assertJsonValidationErrors([
        'password'  =>  __('validation.max.string', ['attribute' => 'password', 'max' => 255])
    ]);
});

it('password should be confirmed', function () {
    postJson(route('register'), [
        'password' => 'teste@123',
        'password_confirmation' => ''
    ])->assertJsonValidationErrors([
        'password' => __('validation.confirmed', ['attribute' => 'password'])
    ]);
});

it('password should have at least 1 uppercase', function () {
    postJson(route('register'), [
        'password' => 'password-without-uppercase'
    ])->assertJsonValidationErrors([
        'password' => 'The password field must contain at least one uppercase and one lowercase letter.'
    ]);
});
