<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;

class TravelRequestPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, TravelRequest $travelRequest)
    {
        return $user->id === $travelRequest->user_id;
    }

    public function viewAny(User $user)
    {
        return $user !== null;
    }

    public function update(User $user)
    {
        return $user->isAdmin();
    }
}
