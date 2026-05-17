<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    // 1. Afficher tous les quiz
    public function index()
    {
        $polls = Poll::withCount('questions')->latest()->get();
        return view('polls.index', compact('polls'));
    }

    // 2. Afficher le formulaire de création
    public function create()
    {
        return view('polls.create');
    }

    // 3. ENREGISTRER le Quiz, les Questions et les Options
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.title' => 'required|string',
            'questions.*.options' => 'required|array|min:1',
            'questions.*.correct' => 'required',
        ]);

        DB::transaction(function () use ($validated) {
            $poll = Poll::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['questions'] as $qData) {
                $question = $poll->questions()->create([
                    'title' => $qData['title'],
                ]);

                foreach ($qData['options'] as $oIndex => $oLabel) {
                    $question->options()->create([
                        'label' => $oLabel,
                        'is_correct' => ($qData['correct'] == $oIndex),
                        'votes_count' => 0,
                    ]);
                }
            }
        });

        return redirect()->route('polls.index')->with('success', 'Félicitations ! Ton Quiz est prêt.');
    }

    // 4. Afficher le quiz complet (avec vérification de session)
    public function show(Poll $poll)
    {
        $poll->load('questions.options');

        // On vérifie si l'ID de ce quiz est déjà dans le tableau "completed_polls" en session
        $completedPolls = session()->get('completed_polls', []);
        $alreadyDone = in_array($poll->id, $completedPolls);

        return view('polls.show', compact('poll', 'alreadyDone'));
    }

    // 5. Action pour CALCULER LE SCORE et BLOQUER le quiz
    public function vote(Request $request, Poll $poll)
    {
        // Sécurité supplémentaire : si l'utilisateur essaie de forcer l'envoi
        $completedPolls = session()->get('completed_polls', []);
        if (in_array($poll->id, $completedPolls)) {
            return redirect()->route('polls.show', $poll->id)
                             ->with('error', 'Vous avez déjà soumis vos réponses.');
        }

        $userAnswers = $request->input('answers'); 

        if (!$userAnswers || count($userAnswers) == 0) {
            return back()->with('error', 'Veuillez répondre aux questions avant de valider.');
        }

        $score = 0;
        $totalQuestions = $poll->questions()->count();

        foreach ($userAnswers as $questionId => $optionId) {
            $option = Option::find($optionId);
            if ($option) {
                $option->increment('votes_count');
                if ($option->is_correct) {
                    $score++;
                }
            }
        }

        // --- NOUVEAUTÉ : ON ENREGISTRE LA FIN DU QUIZ EN SESSION ---
        session()->push('completed_polls', $poll->id);

        $perf = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
        $emoji = $perf >= 50 ? '🎉' : '📚';

        return redirect()->route('polls.show', $poll->id)
                         ->with('success', "Quiz terminé $emoji ! Votre score : $score / $totalQuestions.");
    }
}