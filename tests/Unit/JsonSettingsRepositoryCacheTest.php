<?php

use App\Contracts\SettingsRepository;
use App\Helpers\Helpers;

beforeEach(function (): void {
    Helpers::setTestSettingsOverride(null);
});

afterEach(function (): void {
    Helpers::setTestSettingsOverride(null);
});

it('returns updated settings after put() within the same request', function (): void {
    /** @var SettingsRepository $repo */
    $repo = app(SettingsRepository::class);

    $original = $repo->get();
    expect(data_get($original, 'general.club_name'))->toBeString();

    $repo->put([
        ...$original,
        'general' => [
            ...($original['general'] ?? []),
            'club_name' => 'INASA Scrabble Academy',
        ],
    ]);

    $updated = $repo->get();

    expect(data_get($updated, 'general.club_name'))->toBe('INASA Scrabble Academy');
});
