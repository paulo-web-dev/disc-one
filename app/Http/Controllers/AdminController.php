<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /** Dashboard: total de testes, respondidos × pendentes, distribuição de perfis. */
    public function dashboard()
    {
        $totalTests = Test::count();
        $completed = Test::where('status', 'completed')->count();
        $pending = Test::where('status', 'pending')->count();
        $respondents = User::where('role', 'respondent')->count();
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

    /** Lista de respondentes: quem respondeu e quem não respondeu. */
    public function respondents()
    {
        $q = trim((string) request('q'));

        $respondents = User::where('role', 'respondent')
            ->when($q !== '', fn ($query) => $query->where(
                fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")
            ))
            ->with(['tests' => fn ($t) => $t->where('status', 'completed')->orderByDesc('completed_at')])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.respondentes', compact('respondents', 'q'));
    }

    /** Detalhe de um respondente: resultado, gráfico e data. */
    public function showRespondent(User $user)
    {
        $tests = $user->tests()->orderByDesc('created_at')->get();
        $latest = $tests->firstWhere('status', 'completed');
        $profile = $latest && $latest->dominant_profile
            ? config('disc_profiles.'.$latest->dominant_profile)
            : null;

        return view('admin.respondente', compact('user', 'tests', 'latest', 'profile'));
    }

    /** Gestão de compras/pagamentos: quem comprou, status. */
    public function purchases()
    {
        $status = request('status');

        $purchases = Purchase::with(['user', 'test'])
            ->when(in_array($status, ['paid', 'pending'], true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'revenue' => (float) Purchase::where('status', 'paid')->sum('amount'),
            'paid' => Purchase::where('status', 'paid')->count(),
            'pending' => Purchase::where('status', 'pending')->count(),
        ];

        return view('admin.vendas', compact('purchases', 'stats', 'status'));
    }

    /** Gestão de perguntas: visualizar as 24. */
    public function questions()
    {
        $questions = Question::with('phrases')->orderBy('number')->get();

        return view('admin.perguntas', compact('questions'));
    }

    /** Editar uma pergunta (as 4 frases + ativa). */
    public function editQuestion(Question $question)
    {
        $phrases = $question->phrases->keyBy('dimension'); // ['D'=>..., 'I'=>..., 'S'=>..., 'C'=>...]

        return view('admin.pergunta-editar', compact('question', 'phrases'));
    }

    /** Salvar a edição de uma pergunta. */
    public function updateQuestion(Request $request, Question $question)
    {
        $data = $request->validate([
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

        $question->update(['active' => $request->boolean('active')]);

        return redirect()
            ->route('admin.questions')
            ->with('success', "Pergunta {$question->number} atualizada.");
    }
}
