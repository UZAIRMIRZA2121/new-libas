<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                // Check if user exists with the same email
                $user = User::where('email', $googleUser->getEmail())->first();
                
                if ($user) {
                    // Update existing user with google_id
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                    ]);
                } else {
                    // Create a new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => Hash::make(Str::random(24)), // Random password for social login
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(), // Assume Google emails are verified
                    ]);
                }
            }
            
            Auth::login($user);
            
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }
            
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong during Google login. Please try again.');
        }
    }
}
