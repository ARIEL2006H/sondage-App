<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $poll->title }} | Quiz</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .glass-card {
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .question-card {
            border: 1px solid #e9ecef;
            border-radius: 20px;
            background: #ffffff;
            transition: transform 0.2s ease;
        }
        .option-container {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #f0f2f5;
            border-radius: 12px;
            margin-bottom: 8px;
            display: block;
            padding: 12px 15px;
        }
        .option-container:hover {
            border-color: #0d6efd;
            background-color: #f8fbff;
        }
        .btn-check:checked + .option-container {
            background-color: #e7f1ff;
            border-color: #0d6efd;
            color: #0d6efd;
            font-weight: 600;
        }
        .badge-correct {
            background-color: #198754;
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 10px;
        }
        /* Style pour les options quand le quiz est terminé */
        .disabled-option {
            cursor: not-allowed;
            opacity: 0.8;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            
            <a href="{{ route('polls.index') }}" class="text-decoration-none text-secondary mb-4 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Retour aux thèmes
            </a>

            <div class="card glass-card p-4 p-md-5">
                
                {{-- Message de succès / Score --}}
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 text-center p-3">
                        <i class="bi bi-trophy-fill fs-3 d-block mb-2"></i>
                        <span class="fw-bold">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Bloc affiché si le quiz est déjà terminé --}}
                @if($alreadyDone)
                    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-5 text-center p-4">
                        <i class="bi bi-lock-fill text-primary fs-2"></i>
                        <h4 class="fw-bold mt-2">Quiz déjà soumis</h4>
                        <p class="mb-0">Vous avez déjà terminé ce quiz. Vos réponses ont été enregistrées et vous ne pouvez plus voter.</p>
                    </div>
                @endif

                <div class="mb-5 text-center">
                    <span class="badge bg-primary mb-2 px-3 py-2 rounded-pill text-uppercase">Thème du Quiz</span>
                    <h1 class="fw-bold text-dark mb-3">{{ $poll->title }}</h1>
                    <p class="text-muted">
                        {{ $poll->description ?? 'Répondez aux questions ci-dessous.' }}
                    </p>
                </div>

                <form action="{{ route('polls.vote', $poll->id) }}" method="POST">
                    @csrf
                    
                    @foreach($poll->questions as $question)
                        <div class="question-card p-4 mb-4 shadow-sm">
                            <h5 class="fw-bold mb-4 text-primary">
                                <span class="badge bg-soft-primary text-primary border me-2">{{ $loop->iteration }}</span>
                                {{ $question->title }}
                            </h5>

                            <div class="options-list">
                                @foreach($question->options as $option)
                                    <div class="position-relative">
                                        <input type="radio" class="btn-check" 
                                               name="answers[{{ $question->id }}]" 
                                               id="option{{ $option->id }}" 
                                               value="{{ $option->id }}" 
                                               {{ $alreadyDone ? 'disabled' : 'required' }}>
                                        
                                        <label class="option-container border {{ $alreadyDone ? 'disabled-option' : '' }}" for="option{{ $option->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $option->label }}</span>
                                                {{-- On affiche les bonnes réponses seulement si c'est fini --}}
                                                @if($alreadyDone && $option->is_correct)
                                                    <span class="badge-correct"><i class="bi bi-check-lg"></i> Réponse correcte</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="d-grid gap-2 pt-3">
                        @if($alreadyDone)
                            <a href="{{ route('polls.index') }}" class="btn btn-secondary btn-lg rounded-4 fw-bold py-3">
                                <i class="bi bi-house-door-fill me-2"></i> Retour au tableau de bord
                            </a>
                        @else
                            <button type="submit" class="btn btn-primary btn-lg rounded-4 fw-bold shadow-sm py-3">
                                Soumettre mes réponses <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        @endif
                    </div>
                </form>

                <div class="mt-5 pt-4 border-top text-center">
                    <div class="d-flex justify-content-center gap-4 text-secondary small">
                        <span><i class="bi bi-list-check me-1"></i> {{ $poll->questions->count() }} Questions</span>
                        <span><i class="bi bi-calendar-event me-1"></i> {{ $poll->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                Quiz par Ariel • IP : {{ request()->ip() }}
            </p>
        </div>
    </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>