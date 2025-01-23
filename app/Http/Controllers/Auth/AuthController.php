<?php

namespace App\Http\Controllers\Auth;

use App\Enum\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailVerification;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all('id', 'name', 'email', 'avatar')->map(function ($user): User {

            $user->avatar ??= "https://avatar.iran.liara.run/public";
            return $user;

        });

        Gate::authorize('viewAnyUser');

        return response()->json($users);

    }

    /**
     * Login user api
     */

    public function login(Request $request): UserResource|JsonResponse
    {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);


        $user = User::where('email', $data['email'])->with('roles:id,name')->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }


        $token = $user->createToken((string) $user->name . '-AuthToken')->plainTextToken;

        $user['token'] = $token;

        return new UserResource($user);


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
    public function store(Request $request): UserResource
    {
        $data = $request->validate([
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8',
            'name' => 'required|string|min:3|max:20',
            'address' => 'required|string|min:10|max:40',
            'city' => 'required|string|min:3|max:50',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/', 'unique:customers,phone']
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make(value: $data['password']),
            'name' => $data['name'],
        ]);

        $user->customer()->create([
            'address' => $data['address'],
            'city' => $data['city'],
            'phone' => $data['phone']
        ]);

        $roleId = Role::where('name', Roles::Customer->value)->pluck('id')->first();

        $user->roles()->attach($roleId);

        $code = random_int(1000, 9999);

        Cache::remember((string) "verification_email_" . $user->id, 60, function () use ($code): int {
            return $code;
        });

        SendEmailVerification::dispatch($user, $code);

        $token = $user->createToken((string) $user->name . '-AuthToken')->plainTextToken;

        $user['token'] = $token;

        return new UserResource($user);


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $cacheKey = (string) 'user_' . $userId;

        $user = Cache::remember($cacheKey, now()->addDay(), function () use ($request): mixed {

            return $request->user();

        });

        return response()->json($user);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $id = $request->user()->id;



        $data = $request->validate([
            'email' => "required|email:rfc,dns|unique:users,email,{$id}",
            'password' => 'nullable|string|min:8',
            'name' => 'required|string|min:3|max:20',
            'address' => 'required|string|min:10|max:40',
            'city' => 'required|string|min:3|max:50',
            'phone' => ['required', 'regex:/^\+?[0-9]{10,15}$/', "unique:customers,phone,{$id}"]
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        if (!empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        $user->customer()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $data['address'],
                'city' => $data['city'],
                'phone' => $data['phone']
            ]
        );

        return response()->json(['message' => 'User updated successfully'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {

        $user = $request->user();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);


    }

    /**
     * verification email .
     */

    public function verification(Request $request): JsonResponse
    {

        $data = $request->validate(['code' => 'required|integer|digits:4']);
        $user = $request->user();
        $cachedCode = Cache::get("verification_email_{$user->id}");

        if (!$cachedCode)
            return response()->json(['message' => 'Verification code has expired or is invalid.'], 422);

        if ($cachedCode == $data['code']) {

            $user->markEmailAsVerified();
            Cache::forget("verification_email_{$user->id}");
            return response()->json(['message' => 'Email verified successfully.']);
        }

        return response()->json(['message' => 'Invalid verification code.'], 422);

    }



}
