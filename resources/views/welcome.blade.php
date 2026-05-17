<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Mon Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            min-height: 100vh;
            color: white;
            display: flex;
            align-items: center;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

<section class="hero-section">
    {{-- Bouton de déconnexion --}}
    @auth
        <div class="logout-btn">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>
    @endauth

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 p-5 glass-effect">
                <i class="bi bi-patch-question-fill display-1 mb-4 text-warning"></i>
                <h1 class="display-4 fw-bold mb-3">Mon Quiz</h1>
                <p class="lead mb-5">
                    Testez vos connaissances sur l'agro-industrie et le commerce de proximité au Cameroun. 
                    Participez à des sondages et découvrez vos scores en temps réel.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @guest
                        {{-- Utilisateur non connecté --}}
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 fw-bold rounded-pill">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            Créer un compte
                        </a>
                    @else
                        {{-- Utilisateur connecté --}}
                        <a href="{{ route('polls.index') }}" class="btn btn-light btn-lg px-4 fw-bold rounded-pill">
                            <i class="bi bi-play-fill"></i> Voir les Quiz
                        </a>

                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            <i class="bi bi-speedometer2"></i> Mon Dashboard
                        </a>
                        
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('polls.create') }}" class="btn btn-warning btn-lg px-4 fw-bold rounded-pill text-dark">
                                <i class="bi bi-plus-circle"></i> Créer un Quiz
                            </a>
                        @endif
                    @endguest
                </div>
                
                @auth
                    <div class="mt-4">
                        <small class="text-white-50">Connecté en tant que : <strong>{{ auth()->user()->name }}</strong></small>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>

</body>
</html>