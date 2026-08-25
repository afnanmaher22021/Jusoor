<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use App\Models\Opportunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function apply(Request $request, Opportunity $opportunity): RedirectResponse
    {
        abort_unless($opportunity->status === 'open', 422, __('هذه الفرصة غير متاحة للتقديم حالياً.'));
        abort_unless($opportunity->isFull() === false, 422, __('اكتمل عدد المتطوعين لهذه الفرصة.'));

        $exists = $opportunity->applications()->where('user_id', Auth::id())->exists();
        abort_if($exists, 422, __('لقد تقدمت لهذه الفرصة من قبل.'));

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $application = $opportunity->applications()->create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'message' => $data['message'] ?? null,
        ]);

        $opportunity->organization->user->notifications()->create([
            'type' => 'application',
            'title' => __('طلب تطوع جديد'),
            'body' => __('قام ') . Auth::user()->name . __(' بالتقديم على فرصة ') . $opportunity->title,
            'action_url' => route('organization.applications'),
        ]);

        return redirect()
            ->route('opportunities.show', $opportunity)
            ->with('success', __('تم إرسال طلبك بنجاح. ستتم مراجعته من قبل المؤسسة.'));
    }

    public function myApplications(): \Illuminate\View\View
    {
        return view('volunteer.applications', [
            'applications' => Auth::user()->applications()
                ->with('opportunity.organization', 'opportunity.category')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function cancel(Application $application): RedirectResponse
    {
        abort_unless($application->user_id === Auth::id(), 403);
        abort_unless($application->status === 'pending', 422, __('لا يمكن إلغاء طلب تمت معالجته.'));

        $application->update(['status' => 'cancelled']);

        return back()->with('success', __('تم إلغاء الطلب.'));
    }

    public function indexOrganization(): \Illuminate\View\View
    {
        abort_unless(Auth::user()->isOrganization(), 403);

        return view('organization.applications', [
            'applications' => Application::whereHas('opportunity', fn ($q) => $q->where('organization_id', $this->organization()->id))
                ->with('user', 'opportunity')
                ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function respond(Request $request, Application $application): RedirectResponse
    {
        abort_unless(Auth::user()->isOrganization(), 403);
        abort_unless($application->opportunity->organization_id === $this->organization()->id, 403);
        abort_unless($application->status === 'pending', 422, __('تمت معالجة هذا الطلب من قبل.'));

        $data = $request->validate([
            'status' => ['required', 'in:accepted,rejected'],
            'reviewer_note' => ['nullable', 'string', 'max:500'],
        ]);

        $application->update([
            'status' => $data['status'],
            'responded_at' => now(),
            'reviewer_note' => $data['reviewer_note'] ?? null,
        ]);

        $application->user->notifications()->create([
            'type' => 'application_response',
            'title' => $data['status'] === 'accepted' ? __('تم قبول طلبك!') : __('تم رفض طلبك'),
            'body' => $data['status'] === 'accepted'
                ? __('مبروك، تم قبولك للتطوع في فرصة ') . $application->opportunity->title
                : __('نأسف، لم يتم قبول طلبك في فرصة ') . $application->opportunity->title,
            'action_url' => route('volunteer.dashboard'),
        ]);

        return back()->with('success', __('تم تحديث حالة الطلب.'));
    }

    private function organization(): \App\Models\Organization
    {
        return Auth::user()->organization ?? Auth::user()->organization()->create([
            'name' => Auth::user()->name,
            'city' => Auth::user()->city ?? 'القدس',
        ]);
    }
}
