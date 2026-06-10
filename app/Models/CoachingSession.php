<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $coach_id
 * @property int $member_id
 * @property \Illuminate\Support\Carbon $session_date
 * @property int $duration_minutes
 * @property string|null $notes
 * @property int|null $invoice_id
 * @property string $status
 * @property-read Member $coach
 * @property-read Member $member
 * @property-read Invoice|null $invoice
 */
class CoachingSession extends Model
{
    protected $fillable = [
        'coach_id',
        'member_id',
        'session_date',
        'duration_minutes',
        'notes',
        'invoice_id',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    /**
     * @return BelongsTo<Member, $this>
     */
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'coach_id');
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
