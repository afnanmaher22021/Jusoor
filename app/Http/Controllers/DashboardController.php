<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function volunteer(): View
    {
        abort_unless(Auth::user()->isVolunteer(), 403);

        $user = Auth::user();
        $monthHours = $user->currentMonthHours();
        $goal = $user->monthly_hours_goal ?: 1;

        $upcoming = $user->applications()
            ->where('status', 'accepted')
            ->with('opportunity')
            ->whereHas('opportunity', fn ($q) => $q->where('status', 'open'))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recommended = Opportunity::open()
            ->with('organization', 'category')
            ->whereDoesntHave('applications', fn ($q) => $q->where('user_id', $user->id))
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $totalHours = $user->totalApprovedHours();

        return view('volunteer.dashboard', compact('user', 'monthHours', 'goal', 'upcoming', 'recommended', 'totalHours'));
    }

    public function organization(): View
    {
        abort_unless(Auth::user()->isOrganization(), 403);

        $org = Auth::user()->organization ?? Auth::user()->organization()->create([
            'name' => Auth::user()->name,
            'city' => Auth::user()->city ?? 'القدس',
        ]);
        $opportunities = $org->opportunities()->with('applications')->get();

        $activeOpportunities = $opportunities->where('status', 'open')->count();
        $totalApplications = $opportunities->sum(fn ($o) => $o->applications->count());
        $pendingApplications = $opportunities->sum(fn ($o) => $o->applications->where('status', 'pending')->count());

        $newApplications = $org->opportunities()
            ->with(['applications' => fn ($q) => $q->where('status', 'pending')->with('user')])
            ->get()
            ->pluck('applications')
            ->flatten()
            ->sortByDesc('created_at')
            ->take(6);

        $monthlyGrowth = $this->monthlyGrowth($org);

        return view('organization.dashboard', compact(
            'activeOpportunities',
            'totalApplications',
            'pendingApplications',
            'newApplications',
            'monthlyGrowth',
        ));
    }

    public function admin(): View
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.dashboard', [
            'volunteersCount' => User::where('role', 'volunteer')->count(),
            'organizationsCount' => User::where('role', 'organization')->count(),
            'opportunitiesCount' => Opportunity::count(),
            'pendingParticipations' => Participation::where('status', 'pending')->count(),
        ]);
    }

    private function monthlyGrowth($org): array
    {
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->startOfMonth();
        });

        return $months->map(function ($month) use ($org) {
            $volunteers = $org->opportunities()
                ->whereHas('participation', fn ($q) => $q->whereMonth('work_date', $month->month)->whereYear('work_date', $month->year))
                ->with(['participation' => fn ($q) => $q->whereMonth('work_date', $month->month)->whereYear('work_date', $month->year)])
                ->get()
                ->pluck('participation')
                ->flatten()
                ->pluck('user_id')
                ->unique()
                ->count();

            return [
                'label' => $month->translatedFormat('M'),
                'value' => $volunteers,
            ];
        })->values()->all();
    }
}
