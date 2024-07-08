<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Role::class);

        return Role::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $request->validate([
            'privilege' => ['required'],
            'ref_id' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        return Role::create($data);
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return $role;
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $request->validate([
            'privilege' => ['required'],
            'ref_id' => ['required'],
            'user_id' => ['required', 'exists:users'],
        ]);

        $role->update($data);

        return $role;
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json();
    }
}
