<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle user registration (API + Web).
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'      => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|string|min:6|confirmed', // 🔑 confirm password
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name'      => $request->name,
            'last_name' => $request->last_name ?? null,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        // API Response
        if ($request->expectsJson()) {
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data'    => [
                    'token' => $token,
                    'user'  => $user,
                ]
            ], 201);
        }

        // Web Response
        Auth::login($user);
        return redirect()->route('index')->with('success', 'Registration successful!');
    }

    /**
     * Login (API + Web)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }
            return back()->with('error', 'Invalid credentials');
        }

        $user = Auth::user();

        // API Response
        if ($request->expectsJson()) {
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data'    => [
                    'token' => $token,
                    'user'  => $user,
                ],
            ], 200);
        }

        // Web Response
        $request->session()->regenerate();
        return redirect()->route('index');
    }

    /**
     * Logout (API + Web)
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete(); // revoke only the current token
        }

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully',
        ], 200);
    }

    // Web logout (session/cookie)
    public function webLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

}