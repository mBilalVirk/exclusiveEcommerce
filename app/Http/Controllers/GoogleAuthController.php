<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google login
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            // If user doesn't exist, create new user
            // If user doesn't exist, create new user
            if (!$user) {
                // Check if user exists with this email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link Google account to existing user
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    // Create new user using separate name fields
                    $user = User::create([
                        'first_name' => $googleUser->user['given_name'] ?? $googleUser->getName(),
                        'last_name' => $googleUser->user['family_name'] ?? '',
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => bcrypt(str()->random(16)),
                        'role' => 'customer',
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]);
                }
            }

            // Log in user
            Auth::login($user);

            return redirect('/')->with('success', 'Logged in with Google successfully!');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google. Please try again.');
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
