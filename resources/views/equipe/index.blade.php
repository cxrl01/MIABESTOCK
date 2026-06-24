<x-app-layout title="Équipe">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Équipe</h2>
            <p class="page-header-sub">Gérez les comptes et rôles des collaborateurs de votre boutique.</p>
        </div>
        <a href="{{ route('equipe.create') }}" class="btn-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau collaborateur
        </a>
    </div>

    @if($membres->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <h3>Aucun collaborateur pour le moment</h3>
            <p>Ajoutez des membres à votre équipe pour leur déléguer la gestion des ventes ou des stocks.</p>
            <a href="{{ route('equipe.create') }}" class="btn-action">Ajouter un collaborateur</a>
        </div>
    @else
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-light); background: var(--card);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Membres de l'équipe</h3>
                <span style="font-size: 13px; color: var(--text-sec);">{{ $membres->count() }} collaborateur(s)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Collaborateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membres as $membre)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar-circle">
                                        {{ substr($membre->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ $membre->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $membre->email }}</td>
                            <td>
                                @if($membre->estGestionnaire())
                                    <span class="badge-status active" style="background: var(--accent-light); color: var(--accent);">Gestionnaire</span>
                                @elseif($membre->estCommercial())
                                    <span class="badge-status active" style="background: #fdf2f8; color: #db2777;">Commercial</span>
                                @else
                                    <span class="badge-status inactive">{{ ucfirst($membre->role) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($membre->est_actif)
                                    <span class="badge-status active">Actif</span>
                                @else
                                    <span class="badge-status inactive">Suspendu</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="row-actions" style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <form method="POST" action="{{ route('equipe.toggle-status', $membre) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer; color: {{ $membre->est_actif ? 'var(--red)' : 'var(--green)' }};">
                                            @if($membre->est_actif)
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                                Désactiver
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><polyline points="20 6 9 17 4 12"/></svg>
                                                Activer
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('equipe.edit', $membre) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('equipe.destroy', $membre) }}" onsubmit="return confirm('Supprimer ce collaborateur définitivement ?');" style="display: inline;">
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
