<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_VOLUNTEER, User::ROLE_ORGANIZATION])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'organization_name' => ['required_if:role,organization', 'string', 'max:255'],
            'organization_description' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'url', 'max:255'],
            'founded_year' => ['nullable', 'digits:4'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'],
            'birth_date' => $data['birth_date'] ?? null,
            'skills' => $data['skills'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);

        if ($data['role'] === User::ROLE_ORGANIZATION) {
            $user->organization()->create([
                'name' => $data['organization_name'],
                'description' => $data['organization_description'] ?? null,
                'website' => $data['website'] ?? null,
                'founded_year' => $data['founded_year'] ?? null,
                'city' => $data['city'],
                'verified' => false,
            ]);
        }

        Auth::login($user);

        return redirect($this->dashboardRouteFor($user))->with('success', __('مرحباً بك في منصة جسور!'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('بيانات الدخول غير صحيحة.')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->dashboardRouteFor(Auth::user()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function dashboardRouteFor(User $user): string
    {
        return match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_ORGANIZATION => route('organization.dashboard'),
            default => route('volunteer.dashboard'),
        };
    }
}
