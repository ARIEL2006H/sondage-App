<x-app-layout>
<div class="container-fluid container-xl py-5">
    
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 h-100 bg-primary" style="width: 6px;"></div>
        
        <div class="card-body ps-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <span class="badge bg-primary-subtle text-primary badge-status px-3 py-2 rounded-pill mb-3 small fw-bold">
                        <i class="bi bi-bar-chart-line-fill me-1"></i> Tableau de bord des résultats
                    </span>
                    <h1 class="fw-bold text-dark display-6 tracking-tight mt-1">{{ $poll->title }}</h1>
                    <p class="text-secondary mb-0 max-w-3xl">{{ $poll->description ?? 'Aucune description fournie.' }}</p>
                </div>
                <div>
                    <a href="{{ route('polls.index') }}" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold shadow-sm text-nowrap">
                        <i class="bi bi-arrow-left me-2"></i> Retour aux quiz
                    </a>
                </div>
            </div>
        </div>
    </div>

    @foreach($poll->questions as $index => $question)
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white">
            <div class="card-body">
                
                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="badge bg-dark fs-6 px-3 py-2 rounded-3">
                        {{ $index + 1 }}
                    </span>
                    <h2 class="fw-bold text-dark h4 m-0 pt-1">{{ $question->title }}</h2>
                </div>

                <div class="row g-5">
                    
                    <div class="col-12 col-lg-6">
                        <div class="d-flex justify-content-between align-items-center border-b pb-2 mb-3">
                            <h3 class="text-muted uppercase small fw-bold tracking-wider m-0 flex items-center">
                                <i class="bi bi-pie-chart-fill text-secondary me-2"></i> Décompte Global
                            </h3>
                            @php $totalVotes = $question->options->sum('votes_count'); @endphp
                            <span class="badge bg-light text-secondary border px-2 py-1">
                                Total : {{ $totalVotes }} vote(s)
                            </span>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @foreach($question->options as $option)
                                @php 
                                    $percentage = $totalVotes > 0 ? ($option->votes_count / $totalVotes) * 100 : 0; 
                                @endphp
                                <div class="p-3 rounded-3 border {{ $option->is_correct ? 'border-success bg-success-subtle bg-opacity-10' : 'border-light bg-light bg-opacity-50' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="fw-semibold text-dark">{{ $option->label }}</span>
                                            @if($option->is_correct)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle small font-medium px-2 py-0.5">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Bonne réponse
                                                </span>
                                            @endif
                                        </div>
                                        <span class="badge bg-white text-secondary border shadow-sm small fw-bold">
                                            {{ $option->votes_count }} vote(s) ({{ round($percentage) }}%)
                                        </span>
                                    </div>
                                    
                                    <div class="progress rounded-pill" style="height: 8px;">
                                        <div class="progress-bar rounded-pill {{ $option->is_correct ? 'bg-success' : 'bg-primary' }}" 
                                             role="progressbar" 
                                             style="width: {{ $percentage }}%" 
                                             aria-valuenow="{{ $percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="border-b pb-2 mb-3">
                            <h3 class="text-muted uppercase small fw-bold tracking-wider m-0 flex items-center">
                                <i class="bi bi-people-fill text-secondary me-2"></i> Choix par Participant
                            </h3>
                        </div>

                        <div class="overflow-hidden border border-light rounded-3 bg-light bg-opacity-20 shadow-inner" style="max-h: 330px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0 bg-white">
                                <thead class="table-light sticky-top top-0" style="z-index: 1;">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 small text-secondary fw-bold uppercase">Participant</th>
                                        <th scope="col" class="px-4 py-3 small text-secondary fw-bold uppercase">Option sélectionnée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hasVotes = false; @endphp
                                    @foreach($userVotes as $userId => $votes)
                                        @php $currentVote = $votes->firstWhere('question_id', $question->id); @endphp
                                        @if($currentVote)
                                            @php $hasVotes = true; @endphp
                                            <tr>
                                                <td class="px-4 py-3 fw-semibold text-dark small">
                                                    {{ $currentVote->user->name }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="badge rounded-3 px-3 py-1.5 {{ $currentVote->option->is_correct ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} small fw-semibold">
                                                        {{ $currentVote->option->label }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    
                                    @if(!$hasVotes)
                                        <tr>
                                            <td colspan="2" class="px-4 py-5 text-center text-muted small italic">
                                                <i class="bi bi-folder-x fs-3 d-block text-light mb-2"></i>
                                                Aucun vote enregistré pour cette question.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

</div>
</x-app-layout>