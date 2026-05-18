<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Option;
use App\Models\Question;
use App\Models\Vote; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

class PollController extends Controller
{
    // 1. Afficher TOUS les quiz de la communauté (Visibles par tout le monde)
    public function index()
    {
        $userId = Auth::id();

        // On récupère ABSOLUMENT TOUS les sondages sans restriction de créateur
        $polls = Poll::withCount('questions')
            ->with(['questions.votes' => function($query) use ($userId) {
                // On charge uniquement les votes de l'utilisateur connecté.
                // Cela permet à la vue de savoir si LUI a répondu, tout en voyant les quiz des autres.
                $query->where('user_id', $userId);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

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
                // Si tu as un champ user_id dans ta table 'polls' pour savoir qui l'a créé, 
                // tu peux l'ajouter ici : 'user_id' => Auth::id(),
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

    // 4. Afficher le quiz complet (Vérification via Base de données + Session en secours)
    public function show(Poll $poll)
    {
        $poll->load('questions.options');
        $userId = Auth::id();

        // ÉTAPE SÉCURISÉE BDD : On cherche si un vote de cet utilisateur existe pour ce sondage précis
        $hasVotedInDatabase = Vote::where('user_id', $userId)
            ->whereHas('question', function ($query) use ($poll) {
                $query->where('poll_id', $poll->id);
            })->exists();

        // Secours session
        $completedPolls = session()->get('completed_polls', []);
        
        $alreadyDone = $hasVotedInDatabase || in_array($poll->id, $completedPolls);

        return view('polls.show', compact('poll', 'alreadyDone'));
    }

    // 5. Action pour CALCULER LE SCORE et BLOQUER le quiz (+ ENREGISTREMENT DES VOTES)
    public function vote(Request $request, Poll $poll)
    {
        $userId = Auth::id();

        // ÉTAPE SÉCURISÉE BDD : Double sécurité à la soumission
        $hasVotedInDatabase = Vote::where('user_id', $userId)
            ->whereHas('question', function ($query) use ($poll) {
                $query->where('poll_id', $poll->id);
            })->exists();

        $completedPolls = session()->get('completed_polls', []);

        if ($hasVotedInDatabase || in_array($poll->id, $completedPolls)) {
            return redirect()->route('polls.show', $poll->id)
                             ->with('error', 'Vous avez déjà soumis vos réponses.');
        }

        $userAnswers = $request->input('answers'); 

        if (!$userAnswers || count($userAnswers) == 0) {
            return back()->with('error', 'Veuillez répondre aux questions avant de valider.');
        }

        $score = 0;
        $totalQuestions = $poll->questions()->count();

        DB::transaction(function () use ($userAnswers, $poll, &$score, $userId) {
            foreach ($userAnswers as $questionId => $optionId) {
                $option = Option::find($optionId);
                if ($option) {
                    $option->increment('votes_count');
                    
                    if ($option->is_correct) {
                        $score++;
                    }

                    if (Auth::check()) {
                        Vote::create([
                            'user_id'     => $userId,
                            'question_id' => $questionId,
                            'option_id'   => $optionId
                        ]);
                    }
                }
            }
        });

        session()->push('completed_polls', $poll->id);

        $perf = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
        $emoji = $perf >= 50 ? '🎉' : '📚';

        return redirect()->route('polls.show', $poll->id)
                         ->with('success', "Quiz terminé $emoji ! Votre score : $score / $totalQuestions.");
    }

    // 6. Page des résultats statistiques et nominatifs
    public function results(Poll $poll)
    {
        $poll->load(['questions.options' => function($query) {
            $query->withCount('votes');
        }]);

        $userVotes = Vote::with(['user', 'question', 'option'])
            ->whereHas('question', function($query) use ($poll) {
                $query->where('poll_id', $poll->id);
            })
            ->get()
            ->groupBy('user_id');

        return view('polls.results', compact('poll', 'userVotes'));
    }
}