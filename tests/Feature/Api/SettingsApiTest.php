<?php

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

afterEach(function (): void {
    Helpers::setTestSettingsOverride(null);
});

it('reads and updates settings via the API', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/settings')
        ->assertSuccessful()
        ->assertJsonStructure(['data']);

    $response = $this->putJson('/api/v1/settings', [
        'general' => [
            'club_name' => 'Demo club',
        ],
    ])->assertSuccessful();

    expect((string) $response->json('data.general.club_name'))->toBe('Demo club');
});
