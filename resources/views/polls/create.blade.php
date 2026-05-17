<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Quiz | PollApp</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .card-premium { border: none; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .question-card { background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 25px; border: 1px solid #e9ecef; position: relative; }
        .btn-primary-custom { background-color: #0d6efd; border: none; border-radius: 12px; padding: 12px; transition: all 0.3s; }
        .btn-primary-custom:hover { background-color: #0b5ed7; transform: translateY(-2px); }
        .form-control { border-radius: 10px; padding: 10px; }
        .input-group-text { border-radius: 10px 0 0 10px !important; }
        .btn-remove-option { border-radius: 0 10px 10px 0 !important; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <div class="card card-premium p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="display-6 fw-bold text-primary mb-2">
                        <i class="bi bi-patch-check-fill"></i> QuizApp
                    </div>
                    <h2 class="h4 text-secondary">Nouveau Quiz</h2>
                </div>

                <form action="{{ route('polls.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Titre du Quiz</label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="Ex: Culture Générale" required>
                    </div>

                    <div id="questions-container">
                        <div class="question-card" id="question_0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-dark mb-0">Question n°1</h5>
                            </div>
                            
                            <input type="text" name="questions[0][title]" class="form-control mb-3" placeholder="Quelle est votre question ?" required>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="small fw-bold text-muted text-uppercase">Options (Cochez la bonne réponse)</label>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" onclick="addOptionToQuestion(0)">
                                    <i class="bi bi-plus-circle-fill"></i> Ajouter une option
                                </button>
                            </div>
                            
                            <div id="options-container-0" class="options-list">
                                <div class="input-group mb-2">
                                    <div class="input-group-text bg-white">
                                        <input type="radio" name="questions[0][correct]" value="0" checked>
                                    </div>
                                    <input type="text" name="questions[0][options][]" class="form-control" placeholder="Option 1" required>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-text bg-white">
                                        <input type="radio" name="questions[0][correct]" value="1">
                                    </div>
                                    <input type="text" name="questions[0][options][]" class="form-control" placeholder="Option 2" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="addQuestion()">
                            <i class="bi bi-plus-circle me-1"></i> Ajouter une question
                        </button>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary-custom text-white fw-bold shadow">
                            Enregistrer le Quiz <i class="bi bi-check-all ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <p class="text-center mt-4 text-muted small"><Em>Engueno Noumbou ARIEL</Em> &bull; Laravel Engine</p>
        </div>
    </div>
</div>

<script>
    let questionCount = 1;

    // Fonction pour ajouter une NOUVELLE QUESTION
    function addQuestion() {
        const container = document.getElementById('questions-container');
        const qIndex = questionCount;
        
        const html = `
            <div class="question-card" id="question_${qIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-dark mb-0">Question n°${qIndex + 1}</h5>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
                <input type="text" name="questions[${qIndex}][title]" class="form-control mb-3" placeholder="Votre question ?" required>
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="small fw-bold text-muted text-uppercase">Options</label>
                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" onclick="addOptionToQuestion(${qIndex})">
                        <i class="bi bi-plus-circle-fill"></i> Ajouter une option
                    </button>
                </div>
                
                <div id="options-container-${qIndex}" class="options-list">
                    <div class="input-group mb-2">
                        <div class="input-group-text bg-white">
                            <input type="radio" name="questions[${qIndex}][correct]" value="0" checked>
                        </div>
                        <input type="text" name="questions[${qIndex}][options][]" class="form-control" placeholder="Option 1" required>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        questionCount++;
    }

    // Fonction pour ajouter une OPTION à une question spécifique
    function addOptionToQuestion(qIndex) {
        const container = document.getElementById(`options-container-${qIndex}`);
        const optionCount = container.children.length;
        
        const newOption = document.createElement('div');
        newOption.className = 'input-group mb-2';
        newOption.innerHTML = `
            <div class="input-group-text bg-white">
                <input type="radio" name="questions[${qIndex}][correct]" value="${optionCount}">
            </div>
            <input type="text" name="questions[${qIndex}][options][]" class="form-control" placeholder="Nouvelle option" required>
            <button type="button" class="btn btn-outline-danger btn-remove-option" onclick="this.parentElement.remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newOption);
    }
</script>

</body>
</html>