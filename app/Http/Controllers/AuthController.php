<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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

        if ($user) {
            return [
                "status" => "success",
                "message" => "User created successfully",
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
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (! $toke = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $expiresAt = Carbon::now()->addMinutes(auth()->factory()->getTTL());

        /*$token = 'ulr29' . '.' . Str::random(40);
        $user->api_token = $token;
        $user->save();*/
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'jwt' => $toke,
            'expiresIn' => auth()->factory()->getTTL() * 60,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);

    }
}
