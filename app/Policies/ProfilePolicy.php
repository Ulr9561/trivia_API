<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return Role::where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function view(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    public function create(User $user): bool
    {
        return Role::where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    public function delete(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    public function restore(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    public function forceDelete(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
