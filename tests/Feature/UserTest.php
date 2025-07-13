<?php

use App\Models\User;

it('an authenticated user can access the products API', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/products');
    expect($response->status())->toBe(200);
    expect($response->json())->toBeArray();
});
