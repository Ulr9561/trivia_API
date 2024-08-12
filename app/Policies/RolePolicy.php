<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function view(User $user, Role $role): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function create(User $user): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function update(User $user, Role $role): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function delete(User $user, Role $role): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function restore(User $user, Role $role): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return (new Role)->where("user_id", "=", $user->id)->privilege === 'admin';
    }
}
