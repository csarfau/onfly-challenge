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

it('should cannot logout unauthenticated user', function () {
    postJson(route('logout'))
        ->assertUnauthorized();
});

// it('should cand refresh a jwt token', function () {
//     $token = loginUserAndGetToken();

//     $response = postJson(route('refresh'), [], ['Authorization' => 'Bearer ' . $token])
//         ->assertOk
//         ->assertJsonStructure([
//             'access_token',
//             'token_type',
//             'expires_in'
//         ]);

//     assertNotEquals($token, $response->json('access_token'));
// });
