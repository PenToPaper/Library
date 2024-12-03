<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ReviewPolicy
{
    private function isOwner(User $user, Review $review)
    {
        return $review->user_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->role === 'customer';
    }

    public function update(User $user, Review $review)
    {
        return $this->isOwner($user, $review);
    }

    public function destroy(User $user, Review $review)
    {
        return $this->isOwner($user, $review);
    }
}
