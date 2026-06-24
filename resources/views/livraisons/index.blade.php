<x-app-layout title="Livraisons">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Livraisons</h2>
            <p class="page-header-sub">Historique des livraisons reçues de vos fournisseurs.</p>
        </div>
        <a href="{{ route('livraisons.create') }}" class="btn-action">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle livraison
        </a>
    </div>

    @if($livraisons->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <h3>Aucune livraison pour le moment</h3>
            <p>Enregistrez votre première livraison pour mettre à jour votre stock.</p>
            <a href="{{ route('livraisons.create') }}" class="btn-action">Enregistrer une livraison</a>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Livraison</th>
                        <th>Fournisseur</th>
                        <th>Montant total</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($livraisons as $livraison)
                        <tr>
                            <td class="font-medium">{{ $livraison->numero }}</td>
                            <td class="text-muted">{{ $livraison->fournisseur->nom ?? '—' }}</td>
                            <td>{{ number_format($livraison->total_ttc, 0, ',', ' ') }} F</td>
                            <td class="text-muted">{{ $livraison->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-right">
                                <div class="row-actions">
                                    <a href="{{ route('livraisons.show', $livraison) }}" class="icon-btn-sm" title="Voir le détail">
                                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('livraisons.destroy', $livraison) }}" onsubmit="return confirm('Annuler cette livraison ? Le stock sera ajusté en conséquence.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn-sm icon-btn-danger" title="Annuler">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>