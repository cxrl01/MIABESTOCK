<x-app-layout title="Dépenses">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Dépenses & Trésorerie</h2>
            <p class="page-header-sub">Suivi de vos charges et flux financiers.</p>
        </div>
        <a href="{{ route('depenses.create') }}" class="btn-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px;height:16px;margin-right:4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle dépense
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:24px;">
        <div class="stat-card-horizontal">
            <div class="stat-icon-container red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ number_format($totalMois, 0, ',', ' ') }} F</span>
                <span class="stat-label">Total ce mois</span>
            </div>
        </div>
        <div class="stat-card-horizontal">
            <div class="stat-icon-container green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ number_format($totalAnnee, 0, ',', ' ') }} F</span>
                <span class="stat-label">Total cette année</span>
            </div>
        </div>
        <div class="stat-card-horizontal">
            <div class="stat-icon-container accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ $depenses->count() }}</span>
                <span class="stat-label">Total dépenses</span>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div style="display:flex;gap:12px;margin-bottom:20px;align-items:center;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:240px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Rechercher une dépense..." class="form-input" style="padding-left:36px;height:40px;margin-bottom:0;">
        </div>
        <select id="cat-filter" class="form-input" style="width:200px;height:40px;margin-bottom:0;">
            <option value="all">Toutes les catégories</option>
            <option value="Loyer">Loyer</option>
            <option value="Salaires">Salaires</option>
            <option value="Électricité">Électricité</option>
            <option value="Transport">Transport</option>
            <option value="Fournitures">Fournitures</option>
            <option value="Autres">Autres</option>
        </select>
    </div>

    @if($depenses->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            <h3>Aucune dépense enregistrée</h3>
            <p>Commencez à suivre vos charges pour mieux gérer votre trésorerie.</p>
            <a href="{{ route('depenses.create') }}" class="btn-action">Enregistrer une dépense</a>
        </div>
    @else
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border-light);">
                <h3 style="font-size:15px;font-weight:700;color:var(--text);">Historique des dépenses</h3>
                <span style="font-size:13px;color:var(--text-sec);" id="dep-count">{{ $depenses->count() }} dépense(s)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>Enregistré par</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($depenses as $depense)
                        <tr data-libelle="{{ strtolower($depense->libelle) }}" data-cat="{{ $depense->categorie }}">
                            <td class="text-muted">{{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</td>
                            <td class="font-medium">{{ $depense->libelle }}</td>
                            <td>
                                @if($depense->categorie)
                                    <span class="badge-category">{{ $depense->categorie }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="color:var(--red);font-weight:700;">{{ number_format($depense->montant, 0, ',', ' ') }} F</td>
                            <td class="text-muted">{{ $depense->user->name ?? '—' }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('depenses.destroy', $depense) }}" onsubmit="return confirm('Supprimer cette dépense ?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn-sm icon-btn-danger" title="Supprimer">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const catFilter = document.getElementById('cat-filter');
            const rows = document.querySelectorAll('.data-table tbody tr');
            const count = document.getElementById('dep-count');

            function filter() {
                const q = searchInput.value.toLowerCase().trim();
                const cat = catFilter.value;
                let n = 0;
                rows.forEach(row => {
                    const libelle = row.getAttribute('data-libelle') || '';
                    const rowCat = row.getAttribute('data-cat') || '';
                    const ok = libelle.includes(q) && (cat === 'all' || rowCat === cat);
                    row.style.display = ok ? '' : 'none';
                    if (ok) n++;
                });
                if (count) count.innerText = n + ' dépense(s)';
            }
            searchInput.addEventListener('input', filter);
            catFilter.addEventListener('change', filter);
        });
    </script>

</x-app-layout>
