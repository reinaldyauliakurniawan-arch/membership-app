<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $member_id
 * @property string|null $specialty
 * @property string|null $bio
 * @property float|null $hourly_rate
 * @property-read Member $member
 */
class CoachDetail extends Model
{
    protected $fillable = [
        'member_id',
        'specialty',
        'bio',
        'hourly_rate',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
