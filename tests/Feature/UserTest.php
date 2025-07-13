<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('an authenticated user can access the products API', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/products');

    $response->assertStatus(200);
});

test('a user can register', function () {
    $userData = User::factory()->make()->toArray();
    $userData['password'] = 'P4ssw0rd.25';
    $userData['password_confirmation'] = 'P4ssw0rd.25';

    $response = $this->postJson('/api/register', $userData);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['user', 'token']);
});

test('a user can login', function () {
    $password = 'P4ssw0rd.25';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response
        ->assertStatus(200)
        ->assertJsonStructure(['user', 'token']);
});

test('an authenticated user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/logout');

    $response->assertStatus(200);
});

test('an authenticated user can access their user info', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/user');

    $response->assertStatus(200)->assertJson(['email' => $user->email]);
});