<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function show(): View
    {
        $user = Auth::user();
        abort_unless($user->isVolunteer(), 403);

        $participations = $user->participation()
            ->where('status', 'approved')
            ->with('opportunity.organization')
            ->orderBy('work_date', 'asc')
            ->get();

        $totalHours = (float) $participations->sum('hours');
        $opportunitiesCount = $participations->pluck('opportunity_id')->unique()->count();
        $organizationsCount = $participations->map(fn ($p) => $p->opportunity?->organization_id)->filter()->unique()->count();

        $certificateNumber = 'JSR-' . date('Y') . '-' . str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);

        return view('volunteer.certificate', compact(
            'user',
            'participations',
            'totalHours',
            'opportunitiesCount',
            'organizationsCount',
            'certificateNumber'
        ));
    }
}
