<x-admin-layout title="Boutiques">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Supervision des boutiques</h2>
            <p class="page-header-sub">Vue d'ensemble de toutes les boutiques MiabéStock.</p>
        </div>
    </div>

    <!-- STATS GLOBALES -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Boutiques</div>
                    <div class="stat-sub">Total enregistrées</div>
                </div>
            </div>
            <div class="stat-value">{{ $totalBoutiques }}</div>
            <div class="stat-trend">{{ $totalBoutiquesActives }} active(s)</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Utilisateurs</div>
                    <div class="stat-sub">Tous rôles confondus</div>
                </div>
            </div>
            <div class="stat-value">{{ $totalUtilisateurs }}</div>
            <div class="stat-trend">Gérants, Gestionnaires, Commerciaux</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Chiffre d'affaires</div>
                    <div class="stat-sub">Toutes boutiques</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalVentes, 0, ',', ' ') }} F</div>
            <div class="stat-trend">Cumul de toutes les ventes</div>
        </div>
    </div>

    @if($boutiques->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
            <h3>Aucune boutique enregistrée</h3>
            <p>Les boutiques apparaîtront ici dès qu'un Gérant s'inscrira sur la plateforme.</p>
        </div>
    @else
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Boutique</th>
                        <th>Utilisateurs</th>
                        <th>Produits</th>
                        <th>Clients</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boutiques as $boutique)
                        <tr>
                            <td class="font-medium">
                                <a href="{{ route('admin.boutiques.show', $boutique) }}" style="color: var(--accent);">
                                    {{ $boutique->nom }}
                                </a>
                            </td>
                            <td>{{ $boutique->users_count }}</td>
                            <td>{{ $boutique->produits_count }}</td>
                            <td>{{ $boutique->clients_count }}</td>
                            <td>
                                @if($boutique->statut === 'active')
                                    <span class="badge-statut badge-statut-soldee">Active</span>
                                @elseif($boutique->statut === 'suspendue')
                                    <span class="badge-statut badge-statut-partielle">Suspendue</span>
                                @else
                                    <span class="badge-statut badge-statut-annulee">Supprimée</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $boutique->created_at->format('d/m/Y') }}</td>
                            <td class="text-right">
                                <div class="row-actions">
                                    @if($boutique->statut === 'active')
                                        <button type="button" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;"
                                            onclick="ouvrirModaleMotif('{{ route('admin.boutiques.suspend', $boutique) }}', 'suspend', '{{ $boutique->nom }}')">
                                            Suspendre
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.boutiques.reactivate', $boutique) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action" style="padding: 6px 12px; font-size: 12px;">Réactiver</button>
                                        </form>
                                    @endif
                                    <button type="button" class="icon-btn-sm icon-btn-danger" title="Supprimer"
                                        onclick="ouvrirModaleMotif('{{ route('admin.boutiques.destroy', $boutique) }}', 'destroy', '{{ $boutique->nom }}')">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modal Motif (Suspendre / Supprimer) -->
    <div id="motif-modal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div class="modal-content" style="background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; max-width: 440px; width: 90%; box-shadow: var(--shadow); margin: auto;">
            <h3 id="motifModalTitle" style="font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Suspendre la boutique</h3>
            <p id="motifModalText" style="font-size: 13.5px; color: var(--text-sec); margin-bottom: 16px;"></p>

            <form id="motifForm" method="POST">
                @csrf
                <input type="hidden" id="motifMethod" name="_method" value="PATCH">

                <div class="form-group">
                    <label for="motif" class="form-label">Motif (obligatoire)</label>
                    <textarea id="motif" name="motif" class="form-textarea" rows="3" required placeholder="Expliquez la raison de cette action..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" id="motifSubmitBtn" class="btn-action" style="background: var(--red);">Confirmer</button>
                    <button type="button" class="btn-secondary" onclick="document.getElementById('motif-modal').style.display='none'">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function ouvrirModaleMotif(url, type, nomBoutique) {
            const modal = document.getElementById('motif-modal');
            const form = document.getElementById('motifForm');
            const title = document.getElementById('motifModalTitle');
            const text = document.getElementById('motifModalText');
            const methodInput = document.getElementById('motifMethod');
            const submitBtn = document.getElementById('motifSubmitBtn');

            form.action = url;

            if (type === 'suspend') {
                title.textContent = 'Suspendre la boutique';
                text.textContent = `Vous êtes sur le point de suspendre "${nomBoutique}". Ses utilisateurs ne pourront plus se connecter.`;
                methodInput.value = 'PATCH';
                submitBtn.textContent = 'Suspendre';
            } else {
                title.textContent = 'Supprimer la boutique';
                text.textContent = `Vous êtes sur le point de supprimer définitivement "${nomBoutique}" et toutes ses données. Cette action est irréversible.`;
                methodInput.value = 'DELETE';
                submitBtn.textContent = 'Supprimer définitivement';
            }

            modal.style.display = 'flex';
        }
    </script>

</x-admin-layout>