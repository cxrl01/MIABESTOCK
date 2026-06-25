<x-admin-layout title="Journal d'activité">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Journal d'activité</h2>
            <p class="page-header-sub">Historique des actions importantes sur la plateforme.</p>
        </div>
    </div>

    @if($activites->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <h3>Aucune activité enregistrée</h3>
            <p>Les actions importantes (suspension, réactivation, suppression de boutiques) apparaîtront ici.</p>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Boutique</th>
                        <th>Effectué par</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activites as $activite)
                        <tr>
                            <td>
                                @php
                                    $badgeClass = match(true) {
                                        str_contains($activite->action, 'suspendue') => 'badge-statut-partielle',
                                        str_contains($activite->action, 'supprimee') => 'badge-statut-annulee',
                                        default => 'badge-statut-soldee',
                                    };
                                @endphp
                                <span class="badge-statut {{ $badgeClass }}">{{ str_replace('_', ' ', $activite->action) }}</span>
                            </td>
                            <td class="text-muted">{{ $activite->description }}</td>
                            <td class="font-medium">{{ $activite->boutique->nom ?? '—' }}</td>
                            <td class="text-muted">{{ $activite->user->name ?? 'Système' }}</td>
                            <td class="text-muted">{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $activites->links() }}
        </div>
    @endif

</x-admin-layout>