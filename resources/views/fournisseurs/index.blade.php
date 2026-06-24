<x-app-layout title="Fournisseurs">

    <div class="page-header">
    <div>
        <h2 class="page-header-title">Fournisseurs</h2>
        <p class="page-header-sub">Gérez vos fournisseurs et partenaires commerciaux.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('livraisons.index') }}" class="btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; margin-right: 4px;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Livraisons
        </a>
        <a href="{{ route('fournisseurs.create') }}" class="btn-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau fournisseur
        </a>
    </div>
   </div>

    @if($fournisseurs->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <h3>Aucun fournisseur pour le moment</h3>
            <p>Ajoutez vos fournisseurs pour suivre vos approvisionnements et gérer vos dettes.</p>
            <a href="{{ route('fournisseurs.create') }}" class="btn-action">Ajouter un fournisseur</a>
        </div>
    @else
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-light); background: var(--card);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Liste des fournisseurs</h3>
                <span style="font-size: 13px; color: var(--text-sec);">{{ $fournisseurs->count() }} fournisseur(s)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Dette</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fournisseurs as $fournisseur)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar-circle">{{ substr($fournisseur->nom, 0, 1) }}</div>
                                    <span class="font-medium">{{ $fournisseur->nom }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $fournisseur->telephone ?? '—' }}</td>
                            <td class="text-muted">{{ $fournisseur->email ?? '—' }}</td>
                            <td class="text-muted">{{ $fournisseur->adresse ?? '—' }}</td>
                            <td>
                                @if($fournisseur->solde_dette > 0)
                                    <span class="badge-stock badge-stock-low">{{ number_format($fournisseur->solde_dette, 0, ',', ' ') }} F</span>
                                @else
                                    <span style="color: var(--text-sec); font-size: 13px;">0 F</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="row-actions" style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('fournisseurs.destroy', $fournisseur) }}" onsubmit="return confirm('Supprimer ce fournisseur définitivement ?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn-sm icon-btn-danger" title="Supprimer" style="height: 32px; width: 32px; border-radius: var(--radius-sm);">
                                            <svg viewBox="0 0 24 24" style="width: 14px; height: 14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
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
