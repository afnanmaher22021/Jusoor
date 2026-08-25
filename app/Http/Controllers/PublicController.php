<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function landing(): View
    {
        return view('landing', [
            'featuredOpportunities' => Opportunity::open()
                ->with('organization', 'category')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(),
            'categories' => Category::withCount('opportunities')->get(),
            'stats' => [
                'volunteers' => \App\Models\User::where('role', 'volunteer')->count(),
                'opportunities' => Opportunity::count(),
                'organizations' => \App\Models\User::where('role', 'organization')->count(),
                'hours' => (int) \App\Models\Participation::where('status', 'approved')->sum('hours'),
            ],
        ]);
    }

    public function browse(Request $request): View
    {
        $query = Opportunity::open()->with('organization', 'category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->string('location').'%');
        }

        if ($request->filled('max_hours')) {
            $query->where('required_hours', '<=', $request->integer('max_hours'));
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->string('search').'%');
        }

        return view('browse', [
            'opportunities' => $query->orderByDesc('created_at')->paginate(9)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['category', 'location', 'max_hours', 'search']),
        ]);
    }
}
