<?php

namespace App\Http\Controllers;

use App\Models\Test;

class ConsultantPanelController extends Controller
{
    /** Painel do consultor: seu link + apenas os respondentes que vieram por ele. */
    public function dashboard()
    {
        $consultant = auth()->user();

        $tests = Test::where('consultant_id', $consultant->id)
            ->withCount(['purchases as paid_purchases_count' => fn ($p) => $p->where('status', 'paid')])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => Test::where('consultant_id', $consultant->id)->count(),
            'completed' => Test::where('consultant_id', $consultant->id)->where('status', 'completed')->count(),
        ];

        return view('consultor.dashboard', compact('consultant', 'tests', 'stats'));
    }
}
