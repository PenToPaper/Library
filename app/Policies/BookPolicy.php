<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'librarian';
    }
}
