<x-admin-layout title="Détail boutique">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">{{ $boutique->nom }}</h2>
            <p class="page-header-sub">Créée le {{ $boutique->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('admin.boutiques.index') }}" class="btn-secondary">← Retour à la liste</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Produits</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreProduits }}</div>
        </div>

        <div class="stat-card stat-card-green">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Clients</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreClients }}</div>
        </div>

        <div class="stat-card stat-card-purple">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Ventes</div>
                </div>
            </div>
            <div class="stat-value">{{ $nombreVentes }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Chiffre d'affaires</div>
                </div>
            </div>
            <div class="stat-value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} F</div>
        </div>
    </div>

    <div class="table-card">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-light);">
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Équipe de la boutique</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boutique->users as $user)
                    <tr>
                        <td class="font-medium">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge-count">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @if($user->est_actif)
                                <span class="badge-statut badge-statut-soldee">Actif</span>
                            @else
                                <span class="badge-statut badge-statut-annulee">Désactivé</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-actions" style="margin-top: 24px;">
        @if($boutique->statut === 'active')
            <button type="button" class="btn-secondary"
                onclick="ouvrirModaleMotif('{{ route('admin.boutiques.suspend', $boutique) }}', 'suspend', '{{ $boutique->nom }}')">
                Suspendre cette boutique
            </button>
        @else
            <form method="POST" action="{{ route('admin.boutiques.reactivate', $boutique) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-action">Réactiver cette boutique</button>
            </form>
        @endif

        <button type="button" class="btn-action" style="background: var(--red);"
            onclick="ouvrirModaleMotif('{{ route('admin.boutiques.destroy', $boutique) }}', 'destroy', '{{ $boutique->nom }}')">
            Supprimer définitivement
        </button>
    </div>

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