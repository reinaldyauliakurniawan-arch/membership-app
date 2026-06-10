<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $location
 * @property string $format
 * @property int|null $division_id
 * @property bool $isf_rated
 * @property string $status
 * @property string|null $notes
 * @property-read Division|null $division
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TournamentParticipant> $participants
 */
class Tournament extends Model
{
    protected $fillable = [
        'name',
        'date',
        'location',
        'format',
        'division_id',
        'isf_rated',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'isf_rated' => 'boolean',
    ];

    /**
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * @return HasMany<TournamentParticipant, $this>
     */
    public function participants(): HasMany
    {
        return $this->hasMany(TournamentParticipant::class);
    }
}
