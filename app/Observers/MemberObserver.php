<?php

namespace App\Observers;

use App\Models\Division;
use App\Models\Member;

class MemberObserver
{
    public function saving(Member $member): void
    {
        if ($member->isDirty('current_rating') && $member->current_rating !== null) {
            $division = Division::query()
                ->where('min_rating', '<=', $member->current_rating)
                ->where(function ($query) use ($member) {
                    $query->whereNull('max_rating')
                        ->orWhere('max_rating', '>=', $member->current_rating);
                })
                ->orderBy('min_rating', 'desc')
                ->first();

            $member->division_id = $division?->id;
        }
    }
}
