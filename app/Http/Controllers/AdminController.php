<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.dashboard', [
            'volunteersCount' => User::where('role', User::ROLE_VOLUNTEER)->count(),
            'organizationsCount' => Organization::count(),
            'opportunitiesCount' => Opportunity::count(),
            'totalHours' => (float) Participation::where('status', 'approved')->sum('hours'),
            'organizations' => Organization::with('user')->withCount('opportunities')->orderByDesc('created_at')->get(),
            'recentOpportunities' => Opportunity::with('organization', 'category')->orderByDesc('created_at')->take(6)->get(),
        ]);
    }

    public function toggleVerify(Organization $organization): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $organization->update([
            'verified' => ! $organization->verified,
        ]);

        $status = $organization->verified ? __('تم توثيق واعتماد المؤسسة بنجاح.') : __('تم إلغاء توثيق المؤسسة.');

        return back()->with('success', $status);
    }
}
