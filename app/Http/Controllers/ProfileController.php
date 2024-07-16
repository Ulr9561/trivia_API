<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ProfileController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Profile::class);

        return ProfileResource::collection(Profile::all());
    }

    public function store(ProfileRequest $request)
    {
        $this->authorize('create', Profile::class);

        return new ProfileResource(Profile::create($request->validated()));
    }

    public function show()
    {
        try {
            $user = Auth::user();
            $profile = Profile::where('user_id', $user->id)->firstOrFail();
            $this->authorize('view', $profile);

            return new ProfileResource($profile);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Profile not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        $this->authorize('update', $profile);

        $profile->update($request->validated());

        return new ProfileResource($profile);
    }

    public function destroy(Profile $profile)
    {
        $this->authorize('delete', $profile);

        $profile->delete();

        return response()->json();
    }
}
