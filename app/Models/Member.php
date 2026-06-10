<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Helpers;
use App\Models\Concerns\CascadesSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $photo
 * @property string $code
 * @property string $name
 * @property string|null $email
 * @property string|null $contact
 * @property string|null $emergency_contact

 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property string|null $address
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $pincode
 * @property string|null $source

 * @property Status|null $status
 * @property string|null $isf_id
 * @property int|null $current_rating
 * @property int|null $division_id
 * @property string|null $nationality
 * @property string|null $skill_level
 * @property bool $is_coach
 * @property-read Division|null $division
 * @property-read CoachDetail|null $coachDetail
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CoachingSession> $coachingSessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TournamentParticipant> $tournamentParticipants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Subscription> $subscriptions
 */
class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use CascadesSoftDeletes, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'photo',
        'code',
        'name',
        'email',
        'contact',
        'emergency_contact',

        'gender',
        'dob',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'source',
        'isf_id',
        'current_rating',
        'division_id',
        'nationality',
        'skill_level',
        'is_coach',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'status' => Status::class,
        'is_coach' => 'boolean',
        'current_rating' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     * (SoftDeletes already adds deleted_at rollover.)
     *
     * @var list<string>
     */
    protected $dates = [
        'dob',
        'deleted_at',
    ];

    /**
     * Get the subscriptions for the member.
     */
    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * @return HasOne<CoachDetail, $this>
     */
    public function coachDetail(): HasOne
    {
        return $this->hasOne(CoachDetail::class);
    }

    /**
     * @return HasMany<CoachingSession, $this>
     */
    public function coachingSessions(): HasMany
    {
        return $this->hasMany(CoachingSession::class, 'member_id');
    }

    /**
     * @return HasMany<TournamentParticipant, $this>
     */
    public function tournamentParticipants(): HasMany
    {
        return $this->hasMany(TournamentParticipant::class);
    }

    /**
     * Boot the model and add cascade delete and restore behavior.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (self $member): void {
            if (! $member->code) {
                $member->code = Helpers::generateLastNumber('member', Member::class, null, 'code');
            }
            Helpers::updateLastNumber('member', $member->code);
        });
    }

    /**
     * Relationship method names to cascade when deleting/restoring.
     *
     * @return list<string>
     */
    protected static function relationsToCascade(): array
    {
        return ['subscriptions'];
    }
}
