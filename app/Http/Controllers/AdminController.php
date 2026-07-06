<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /** Dashboard: total de testes, respondidos × pendentes, distribuição de perfis. */
    public function dashboard()
    {
        $totalTests = Test::count();
        $completed = Test::where('status', 'completed')->count();
        $pending = Test::where('status', 'pending')->count();

        // Respondentes reais = pessoas distintas que responderam (por e-mail).
        $respondents = Test::whereNotNull('respondent_email')
            ->distinct('respondent_email')
            ->count('respondent_email');

        $revenue = (float) Purchase::where('status', 'paid')->sum('amount');

        $counts = Test::where('status', 'completed')
            ->whereNotNull('dominant_profile')
            ->selectRaw('dominant_profile, COUNT(*) as total')
            ->groupBy('dominant_profile')
            ->pluck('total', 'dominant_profile');

        $distribution = [
            'D' => (int) ($counts['D'] ?? 0),
            'I' => (int) ($counts['I'] ?? 0),
            'S' => (int) ($counts['S'] ?? 0),
            'C' => (int) ($counts['C'] ?? 0),
        ];

        return view('admin.dashboard', compact(
            'totalTests', 'completed', 'pending', 'respondents', 'revenue', 'distribution'
        ));
    }

    /** Lista de respondentes (testes): nome, e-mail, celular, perfil, situação e consultor. */
    public function respondents()
    {
        $q = trim((string) request('q'));
        $consultantId = request('consultant');

        $respondents = Test::query()
            ->with('consultant')
            ->withCount(['purchases as paid_purchases_count' => fn ($p) => $p->where('status', 'paid')])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) =>
                $w->where('respondent_name', 'like', "%{$q}%")
                    ->orWhere('respondent_email', 'like', "%{$q}%")
                    ->orWhere('respondent_phone', 'like', "%{$q}%")
            ))
            ->when($consultantId, fn ($query) => $query->where('consultant_id', $consultantId))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $consultants = User::where('role', 'consultant')->orderBy('name')->get();

        return view('admin.respondentes', compact('respondents', 'q', 'consultants', 'consultantId'));
    }

    /** Relatório financeiro: compras, status e KPIs. */
    public function purchases()
    {
        $status = request('status');

        $purchases = Purchase::with(['user', 'test'])
            ->when(in_array($status, ['paid', 'pending'], true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $revenue = (float) Purchase::where('status', 'paid')->sum('amount');
        $paid = Purchase::where('status', 'paid')->count();

        $stats = [
            'revenue' => $revenue,
            'revenue_month' => (float) Purchase::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'paid' => $paid,
            'pending' => Purchase::where('status', 'pending')->count(),
            'pending_value' => (float) Purchase::where('status', 'pending')->sum('amount'),
            'avg_ticket' => $paid > 0 ? $revenue / $paid : 0.0,
        ];

        return view('admin.vendas', compact('purchases', 'stats', 'status'));
    }

    /** Gestão de perguntas: as 24, com as 4 frases (D/I/S/C) de cada. */
    public function questions()
    {
        $questions = Question::with('phrases')->orderBy('number')->get();

        return view('admin.perguntas', compact('questions'));
    }

    /** Editar uma pergunta (enunciado + 4 frases + ativa). */
    public function editQuestion(Question $question)
    {
        $phrases = $question->phrases->keyBy('dimension');

        return view('admin.pergunta-editar', compact('question', 'phrases'));
    }

    /** Salvar a edição de uma pergunta. */
    public function updateQuestion(Request $request, Question $question)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'phrases' => ['required', 'array'],
            'phrases.D' => ['required', 'string', 'max:500'],
            'phrases.I' => ['required', 'string', 'max:500'],
            'phrases.S' => ['required', 'string', 'max:500'],
            'phrases.C' => ['required', 'string', 'max:500'],
            'active' => ['nullable', 'boolean'],
        ]);

        foreach (['D', 'I', 'S', 'C'] as $dimension) {
            $question->phrases()
                ->where('dimension', $dimension)
                ->update(['phrase' => trim($data['phrases'][$dimension])]);
        }

        $question->update([
            'title' => trim($data['title']),
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('admin.questions')
            ->with('success', "Pergunta {$question->number} atualizada.");
    }

    // ============================================================
    // Consultores / Divulgadores (CRUD)
    // ============================================================

    /** Lista de consultores com link de referral e total de respondentes. */
    public function consultants()
    {
        $consultants = User::where('role', 'consultant')
            ->withCount('referredTests')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.consultores.index', compact('consultants'));
    }

    /** Formulário de novo consultor. */
    public function createConsultant()
    {
        return view('admin.consultores.form', ['consultant' => new User()]);
    }

    /** Cria um consultor. */
    public function storeConsultant(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'referral_code' => ['nullable', 'string', 'max:40', 'alpha_dash', 'unique:users,referral_code'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // o cast "hashed" faz o hash
            'role' => 'consultant',
            'referral_code' => $data['referral_code'] ?: User::generateReferralCode(),
        ]);

        return redirect()->route('admin.consultants')->with('success', 'Consultor criado com sucesso.');
    }

    /** Formulário de edição. */
    public function editConsultant(User $consultant)
    {
        abort_unless($consultant->role === 'consultant', 404);

        return view('admin.consultores.form', compact('consultant'));
    }

    /** Atualiza um consultor. */
    public function updateConsultant(Request $request, User $consultant)
    {
        abort_unless($consultant->role === 'consultant', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($consultant->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'referral_code' => ['required', 'string', 'max:40', 'alpha_dash', Rule::unique('users', 'referral_code')->ignore($consultant->id)],
        ]);

        $consultant->name = $data['name'];
        $consultant->email = $data['email'];
        $consultant->referral_code = $data['referral_code'];

        if (! empty($data['password'])) {
            $consultant->password = $data['password'];
        }

        $consultant->save();

        return redirect()->route('admin.consultants')->with('success', 'Consultor atualizado.');
    }

    /** Remove um consultor (os respondentes dele continuam, sem vínculo). */
    public function destroyConsultant(User $consultant)
    {
        abort_unless($consultant->role === 'consultant', 404);

        $consultant->delete();

        return redirect()->route('admin.consultants')->with('success', 'Consultor removido.');
    }
}
