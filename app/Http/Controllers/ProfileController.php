<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Get user's recent sessions (mock data - implement based on your needs)
        $sessions = $this->getRecentSessions($user);

        return view('profile.edit', [
            'user' => $user,
            'sessions' => $sessions,
            'timezones' => [
                'Asia/Dhaka' => 'Dhaka (GMT+6)',
                'UTC' => 'UTC',
                'America/New_York' => 'New York (EST)',
                'Europe/London' => 'London (GMT)',
                'Asia/Dubai' => 'Dubai (GST)',
                'Asia/Singapore' => 'Singapore (SGT)',
            ],
            'languages' => [
                'en' => 'English',
                'bn' => 'বাংলা (Bengali)',
            ],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Remove avatar from validated if not provided (to avoid overwriting)
        if (!$request->hasFile('avatar') && !isset($validated['avatar'])) {
            unset($validated['avatar']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::back()->with('status', 'profile-updated');
    }

    /**
     * Delete user's avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return Redirect::back()->with('status', 'avatar-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete avatar before account deletion
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get user's recent active sessions (placeholder implementation).
     */
    private function getRecentSessions($user): array
    {
        // This is a placeholder - you can implement actual session tracking
        // using Laravel's session database driver or custom session storage
        return [
            [
                'device' => 'Current Device',
                'browser' => request()->header('User-Agent', 'Unknown'),
                'ip' => request()->ip(),
                'last_active' => now(),
                'is_current' => true,
            ],
        ];
    }
}
