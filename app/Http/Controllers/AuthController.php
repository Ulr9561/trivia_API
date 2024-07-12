<?php

namespace App\Http\Controllers;

use App\Events\UserRegisteredEvent;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $role = Role::create([
            'privilege' => 'user',
            'ref_id' => 2001,
            'user_id' => $user->id,
        ]);

        if ($user) {
            event(new UserRegisteredEvent($user));
            return [
                "status" => "success",
                "message" => "User created successfully",
                "role" => $role->privilege,
            ];
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Something went wrong",
            ], 500);
        }
    }

    /**
     * Login the user.
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|email",
                "password" => "required",
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $credentials = $request->only('email', 'password');

            if (!auth()->attempt($credentials)) {
                throw new UnauthorizedHttpException('', 'Unauthorized');
            }

            $token = auth('api')->claims(['roles' => auth('api')->user()->getRoleIDs()])->attempt($credentials);

            $user = Auth::user();
            $expiresAt = Carbon::now()->addMinutes(auth()->factory()->getTTL());

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'jwt' => $token,
                'expiresIn' => auth()->factory()->getTTL() * 60,
                'expires_at' => $expiresAt->toDateTimeString(),
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => "This credentials doesn't match our records. ". $e->getMessage()], 401);
        } catch (Exception) {
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Show the user details
    */
    public function show(){
        $user = Auth::user();
        return new UserResource($user);
    }
}
