<?php

namespace App\Policies;

use App\Models\User;

class BookPolicy
{
    private function isLibrarian(User $user): bool
    {
        return $user->role === 'librarian';
    }

    public function create(User $user): bool
    {
        return $this->isLibrarian($user);
    }

    public function delete(User $user): bool
    {
        return $this->isLibrarian($user);
    }

    public function update(User $user): bool
    {
        return $this->isLibrarian($user);
    }
}
