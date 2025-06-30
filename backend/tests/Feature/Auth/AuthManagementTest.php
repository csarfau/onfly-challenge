<?php

use App\Models\User;

use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertNotEquals;

function loginUserAndGetToken()
{
    $email = 'test@example.com';
    $password = 'password';

    User::factory()->create([
        'email' => $email,
        'password' => bcrypt($password),
    ]);

    return postJson(route('login'), [
        'email' => $email,
        'password' => $password,
    ])->json('access_token');
}

it('should can logout an authenticated user', function () {
    $token = loginUserAndGetToken();

    postJson(route('logout'), [], ['Authorization' => 'Bearer ' . $token])
        ->assertNoContent();
});

it('requires authentication for logout', function () {
    postJson(route('logout'))
        ->assertUnauthorized();
});

it('should can refresh a jwt token', function () {
    $token = loginUserAndGetToken();

    $response = postJson(route('refresh'), [], ['Authorization' => 'Bearer ' . $token])
        ->assertOk()
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

    assertNotEquals($token, $response->json('access_token'));
});

it('requires authentication for refresh token', function () {
    postJson(route('refresh'))
        ->assertUnauthorized();
});
