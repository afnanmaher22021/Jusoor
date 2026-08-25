<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Opportunity;
use App\Models\Participation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HourTrackingController extends Controller
{
    public function selectOpportunity(): View
    {
        abort_unless(Auth::user()->isOrganization(), 403);

        return view('organization.hours.select', [
            'opportunities' => $this->organization()->opportunities()
                ->with('applications.user')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function manage(Opportunity $opportunity): View
    {
        abort_unless(Auth::user()->isOrganization(), 403);
        abort_unless($opportunity->organization_id === $this->organization()->id, 403);

        $acceptedVolunteers = $opportunity->applications()
            ->where('status', 'accepted')
            ->with('user.participation')
            ->get();

        return view('organization.hours.manage', [
            'opportunity' => $opportunity,
            'volunteers' => $acceptedVolunteers,
        ]);
    }

    public function store(Request $request, Opportunity $opportunity): RedirectResponse
    {
        abort_unless(Auth::user()->isOrganization(), 403);
        abort_unless($opportunity->organization_id === $this->organization()->id, 403);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'work_date' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $isAccepted = $opportunity->applications()
            ->where('user_id', $data['user_id'])
            ->where('status', 'accepted')
            ->exists();

        abort_unless($isAccepted, 422, __('هذا المتطوع غير مقبول في هذه الفرصة.'));

        $participation = Participation::create([
            'user_id' => $data['user_id'],
            'opportunity_id' => $opportunity->id,
            'hours' => $data['hours'],
            'work_date' => $data['work_date'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $participation->update(['status' => 'approved']);

        $participation->user->notifications()->create([
            'type' => 'hours',
            'title' => __('تم إضافة ساعات تطوع'),
            'body' => __('تم تسجيل ' . $data['hours'] . ' ساعة لك في فرصة ') . $opportunity->title,
            'action_url' => route('volunteer.dashboard'),
        ]);

        return back()->with('success', __('تم تسجيل الساعات واعتمادها للمتطوع.'));
    }

    public function history(): View
    {
        abort_unless(Auth::user()->isVolunteer(), 403);

        return view('volunteer.hours', [
            'records' => Auth::user()->participation()
                ->with('opportunity.organization')
                ->orderByDesc('work_date')
                ->get(),
        ]);
    }

    private function organization(): \App\Models\Organization
    {
        return Auth::user()->organization ?? Auth::user()->organization()->create([
            'name' => Auth::user()->name,
            'city' => Auth::user()->city ?? 'القدس',
        ]);
    }
}
