<x-app-layout>
<div class="container py-5">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-5">
        <div>
            <h1 class="fw-bold text-dark display-6">Tableau de bord</h1>
            <p class="text-secondary mb-0 fs-5">Découvrez les quiz de la communauté</p>
        </div>
        <div>
            <a href="{{ route('polls.create') }}" class="btn btn-primary rounded-3 px-4 py-2.5 fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> Nouveau Quiz
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($polls as $poll)
            @php
                // NOUVELLE LOGIQUE PERSISTANTE (Base de données) :
                // On vérifie de façon instantanée si l'utilisateur possède un vote enregistré dans ce sondage
                $alreadyDone = $poll->questions->flatMap->votes->isNotEmpty();
            @endphp
            
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card poll-card border-0 shadow-sm rounded-4 h-100 p-3 bg-white transition-all">
                    <div class="card-body d-flex flex-column">
                        
                        <div class="mb-3">
                            @if($alreadyDone)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill small fw-bold text-uppercase tracking-wider">
                                    <i class="bi bi-check2-circle me-1"></i> Terminé
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill small fw-bold text-uppercase tracking-wider">
                                    Actif
                                </span>
                            @endif
                        </div>
                        
                        <h5 class="card-title fw-bold text-dark mb-2 h4">{{ $poll->title }}</h5>
                        <p class="card-text text-muted flex-grow-1 small">
                            {{ Str::limit($poll->description, 100, '...') }}
                        </p>

                        <div class="mt-3 text-secondary small fw-medium">
                            <i class="bi bi-question-circle-fill text-muted me-1"></i> 
                            {{ $poll->questions_count ?? ($poll->questions ? $poll->questions->count() : 0) }} questions
                        </div>

                        <div class="mt-4 pt-3 border-top border-light d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            @if($alreadyDone)
                                <a href="{{ route('polls.results', $poll->id) }}" class="btn btn-purple btn-sm rounded-3 py-2 w-100 text-white fw-bold shadow-sm" style="background-color: #6f42c1;">
                                    <i class="bi bi-bar-chart-line-fill me-1"></i> Voir les résultats
                                </a>
                            @else
                                <a href="{{ route('polls.show', $poll->id) }}" class="btn btn-outline-primary btn-sm rounded-3 px-4 py-2 fw-bold">
                                    <i class="bi bi-controller me-1"></i> Jouer
                                </a>
                                <a href="{{ route('polls.results', $poll->id) }}" class="btn btn-link text-decoration-none text-secondary btn-sm fw-semibold p-0 small">
                                    <i class="bi bi-bar-chart-line me-1"></i> Scores globaux
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white p-5 rounded-4 border border-light shadow-sm max-w-2xl mx-auto">
                    <i class="bi bi-inbox text-muted display-1 d-block mb-3"></i>
                    <h3 class="fw-bold text-dark">Aucun quiz pour le moment</h3>
                    <p class="text-secondary mb-4">Soyez le premier de la communauté à lancer un défi !</p>
                    <a href="{{ route('polls.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold">
                        Créer un quiz maintenant
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .poll-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .poll-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06) !important;
    }
    .btn-purple:hover {
        background-color: #59359a !important;
    }
</style>
</x-app-layout>