<?php

namespace App\Http\Controllers\Auth;

use App\Enum\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Login user api
     */

    public function login(Request $request): JsonResponse
    {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);


        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken((string) $user->name . '-AuthToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);

    }

    /**
     * Logout user api
     */

    public function logout(Request $request): JsonResponse
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out!',
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|min:3|max:20'
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['name'],
        ]);


        $token = $user->createToken((string) $user->name . '-AuthToken')->plainTextToken;

        $user['token'] = $token;

        return response()->json(['user' => $user], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $user = Cache::remember((string) 'user_' . $userId, 60 * 5, function () use ($request): mixed {

            $user = $request->user();

            return $user;

        });

        return response()->json($user);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
