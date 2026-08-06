<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function view(User $user, Service $service): bool
    {
        return $user->id === $service->user_id || $user->hasRole('admin');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->id === $service->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->id === $service->user_id && $service->isTerminated();
    }
}
