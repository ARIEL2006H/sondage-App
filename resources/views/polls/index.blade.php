<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sondages disponibles | PollApp</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .poll-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            background: rgba(255, 255, 255, 0.9);
        }
        .poll-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-create {
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }
        .badge-status {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 10px;
            border-radius: 30px;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-dark">Tableau de bord</h1>
            <p class="text-secondary mb-0">Découvrez les quiz de la communauté</p>
        </div>
        <a href="{{ route('polls.create') }}" class="btn btn-primary btn-create">
            <i class="bi bi-plus-lg me-2"></i> Nouveau Quiz
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($polls as $poll)
            <div class="col-md-6 col-lg-4">
                <div class="card poll-card h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary badge-status">Actif</span>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-2">{{ $poll->title }}</h5>
                        <p class="card-text text-muted flex-grow-1 small">
                            {{ Str::limit($poll->description, 100, '...') }}
                        </p>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">
                                <i class="bi bi-question-circle me-1"></i> 
                                {{ $poll->questions_count ?? ($poll->questions ? $poll->questions->count() : 0) }} questions
                            </span>
                            <a href="{{ route('polls.show', $poll->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                Jouer au Quiz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white p-5 rounded-5 shadow-sm">
                    <i class="bi bi-inbox text-light display-1"></i>
                    <h3 class="mt-3 fw-bold">Aucun quiz pour le moment</h3>
                    <p class="text-muted">Soyez le premier à lancer un défi !</p>
                    <a href="{{ route('polls.create') }}" class="btn btn-primary mt-2">Créer maintenant</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>