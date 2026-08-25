<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    public function index(): View
    {
        $this->authorizeRole();

        return view('organization.opportunities.index', [
            'opportunities' => $this->organization()->opportunities()
                ->with('category', 'applications')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorizeRole();

        return view('organization.opportunities.create', [
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRole();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:150'],
            'required_hours' => ['required', 'integer', 'min:1', 'max:999'],
            'max_volunteers' => ['required', 'integer', 'min:1', 'max:9999'],
            'skills_required' => ['nullable', 'string', 'max:2000'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
        ]);

        $this->organization()->opportunities()->create($data);

        return redirect()
            ->route('organization.opportunities.index')
            ->with('success', __('تم نشر الفرصة التطوعية بنجاح.'));
    }

    public function edit(Opportunity $opportunity): View
    {
        $this->authorizeOpportunity($opportunity);

        return view('organization.opportunities.edit', [
            'opportunity' => $opportunity,
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeOpportunity($opportunity);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:150'],
            'required_hours' => ['required', 'integer', 'min:1', 'max:999'],
            'max_volunteers' => ['required', 'integer', 'min:1', 'max:9999'],
            'skills_required' => ['nullable', 'string', 'max:2000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', Rule::in(['open', 'closed', 'completed'])],
        ]);

        $opportunity->update($data);

        return redirect()
            ->route('organization.opportunities.index')
            ->with('success', __('تم تحديث الفرصة التطوعية بنجاح.'));
    }

    public function destroy(Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeOpportunity($opportunity);

        $opportunity->delete();

        return redirect()
            ->route('organization.opportunities.index')
            ->with('success', __('تم حذف الفرصة التطوعية.'));
    }

    public function show(Opportunity $opportunity): View
    {
        $opportunity->load('organization', 'category');

        return view('opportunities.show', [
            'opportunity' => $opportunity,
            'hasApplied' => Auth::check()
                ? $opportunity->applications()->where('user_id', Auth::id())->exists()
                : false,
            'acceptedCount' => $opportunity->acceptedCount(),
        ]);
    }

    private function organization(): Organization
    {
        return Auth::user()->organization ?? Auth::user()->organization()->create([
            'name' => Auth::user()->name,
            'city' => Auth::user()->city ?? 'القدس',
        ]);
    }

    private function authorizeRole(): void
    {
        abort_unless(Auth::user()->isOrganization(), 403, __('هذه العملية خاصة بالمؤسسات فقط.'));
    }

    private function authorizeOpportunity(Opportunity $opportunity): void
    {
        $this->authorizeRole();
        abort_unless($opportunity->organization_id === $this->organization()->id, 403);
    }
}
