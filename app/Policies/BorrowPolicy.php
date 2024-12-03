<?php

namespace App\Policies;

use App\Models\User;

class BorrowPolicy
{
    private function isLibrarian(User $user): bool
    {
        return $user->role === 'librarian';
    }

    public function markReturned(User $user): bool
    {
        return $this->isLibrarian($user);
    }

}
