<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProfilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        $role = Role::where("user_id", $user->id)->first();
        return $role && $role->privilege === 'admin';
    }

    public function view(User $user, Profile $profile): bool
    {
        $user = Auth::user();
        return $user->id === $profile->user_id;
    }

    public function create(User $user): bool
    {
        $role = Role::where("user_id", $user->id)->first();
        return $role && $role->privilege === 'admin';
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
