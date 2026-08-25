<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        if ($user->isOrganization()) {
            $org = $user->organization ?? $user->organization()->create([
                'name' => $user->name,
                'city' => $user->city ?? 'القدس',
            ]);

            return view('organization.profile', compact('user', 'org'));
        }

        return view('volunteer.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ];

        if ($user->isVolunteer()) {
            $rules['skills'] = ['nullable', 'string', 'max:1000'];
            $rules['monthly_hours_goal'] = ['nullable', 'integer', 'min:1', 'max:300'];
            $rules['birth_date'] = ['nullable', 'date', 'before:today'];
        }

        if ($user->isOrganization()) {
            $rules['organization_name'] = ['required', 'string', 'max:255'];
            $rules['organization_description'] = ['nullable', 'string', 'max:3000'];
            $rules['website'] = ['nullable', 'url', 'max:255'];
            $rules['founded_year'] = ['nullable', 'digits:4'];
        }

        $validated = $request->validate($rules);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'city' => $validated['city'],
            'bio' => $validated['bio'] ?? null,
        ];

        if ($user->isVolunteer()) {
            $userData['skills'] = $validated['skills'] ?? null;
            $userData['monthly_hours_goal'] = $validated['monthly_hours_goal'] ?? 10;
            $userData['birth_date'] = $validated['birth_date'] ?? null;
        }

        $user->update($userData);

        if ($user->isOrganization()) {
            $org = $user->organization ?? $user->organization()->create([
                'name' => $validated['organization_name'],
                'city' => $validated['city'],
            ]);

            $org->update([
                'name' => $validated['organization_name'],
                'description' => $validated['organization_description'] ?? null,
                'website' => $validated['website'] ?? null,
                'founded_year' => $validated['founded_year'] ?? null,
                'city' => $validated['city'],
            ]);
        }

        return back()->with('success', __('تم تحديث بيانات الملف الشخصي بنجاح.'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', __('تم تغيير كلمة المرور بنجاح.'));
    }
}
