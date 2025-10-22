<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|max:50|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function registerAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole->id);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin account created successfully!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($request->expectsJson() === false) {
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();
                $user->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);
                
                if ($user->hasRole('admin')) {
                    return redirect()->intended(route('admin.dashboard'));
                }
                
                return redirect('/');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        
        $user->tokens()->where('name', 'auth_token')->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $user->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);
        
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $user->update([
                'last_logout_at' => now(),
                'last_logout_ip' => $request->ip(),
            ]);
        }
        
        if ($request->expectsJson() === false) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login');
        }

        $user->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        
        $request->user()->currentAccessToken()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'token' => $token
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|nullable|string|max:30',
            'address' => 'sometimes|nullable|string',
            'avatar' => 'sometimes|nullable|string',
        ]);
        $user->update($data);
        return response()->json($user->refresh());
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = $request->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['Current password is incorrect']]);
        }
        $user->update(['password' => Hash::make($data['password'])]);
        return response()->json(['message' => 'Password updated']);
    }

    public function forgotPassword(Request $request)
    {
        return response()->json(['message' => 'Password reset link sent (stub)']);
    }

    public function resetPassword(Request $request)
    {
        return response()->json(['message' => 'Password has been reset (stub)']);
    }
}
