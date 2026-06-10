<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tournament_id
 * @property int $member_id
 * @property int|null $division_id
 * @property int|null $rating_before
 * @property int|null $rating_after
 * @property int|null $final_rank
 * @property int|null $total_wins
 * @property int|null $total_spread
 * @property float|null $points
 * @property-read Tournament $tournament
 * @property-read Member $member
 * @property-read Division|null $division
 */
class TournamentParticipant extends Model
{
    protected $fillable = [
        'tournament_id',
        'member_id',
        'division_id',
        'rating_before',
        'rating_after',
        'final_rank',
        'total_wins',
        'total_spread',
        'points',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Tournament, $this>
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
