<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $userRole = Role::where('slug', 'user')->firstOrFail();

        $user = DB::transaction(function () use ($validated, $userRole) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            $user->role()->associate($userRole);
            $user->save();

            return $user;
        });

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role && $user->role->slug === 'admin') {
                return redirect('/admin');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }

    // Redirect user to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->getEmail()) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Google did not provide an email address.',
                    ]);
            }

            if (!($googleUser->user['email_verified'] ?? false)) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Your Google email address is not verified.',
                    ]);
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $userRole = Role::where('slug', 'user')->firstOrFail();

                $user = DB::transaction(function () use ($googleUser, $userRole) {
                    $newUser = User::create([
                        'name' => $googleUser->getName() ?: 'Google User',
                        'email' => $googleUser->getEmail(),
                        'password' => Str::random(40),
                    ]);

                    $newUser->role()->associate($userRole);
                    $newUser->save();

                    return $newUser;
                });
            }

            Auth::login($user);

            $request->session()->regenerate();

            if ($user->role && $user->role->slug === 'admin') {
                return redirect('/admin');
            }

            return redirect()->route('dashboard');

        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google login failed. Please try again.',
                ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
