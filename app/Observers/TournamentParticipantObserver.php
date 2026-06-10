<?php

namespace App\Observers;

use App\Models\TournamentParticipant;

class TournamentParticipantObserver
{
    /**
     * When rating_after is set on a completed tournament's participant,
     * sync it back to the member's current_rating.
     * This will in turn trigger MemberObserver to re-assign the division.
     */
    public function saved(TournamentParticipant $participant): void
    {
        if ($participant->rating_after === null) {
            return;
        }

        $participant->loadMissing('tournament');

        if ($participant->tournament->status !== 'completed') {
            return;
        }

        $member = $participant->member;

        if ($member->current_rating === $participant->rating_after) {
            return;
        }

        $member->current_rating = $participant->rating_after;
        $member->save();
    }
}
