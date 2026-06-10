<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $min_rating
 * @property int|null $max_rating
 * @property string|null $description
 * @property int $order
 */
class Division extends Model
{
    protected $fillable = [
        'name',
        'min_rating',
        'max_rating',
        'description',
        'order',
    ];

    protected $casts = [
        'min_rating' => 'integer',
        'max_rating' => 'integer',
        'order' => 'integer',
    ];

    /**
     * @return HasMany<Member, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * @return HasMany<Tournament, $this>
     */
    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }
}
