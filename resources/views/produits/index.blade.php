<x-app-layout title="Produits">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Gestion des Stocks</h2>
            <p class="page-header-sub">Catalogue produits · Mouvements · Alertes</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; margin-right: 6px;"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Catégories
            </a>
            <a href="{{ route('produits.create') }}" class="btn-action">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nouveau produit
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card-horizontal">
            <div class="stat-icon-container accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ $produits->count() }}</span>
                <span class="stat-label">Total produits</span>
            </div>
        </div>

        <div class="stat-card-horizontal">
            <div class="stat-icon-container red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ $produits->filter(fn($p) => $p->estEnStockCritique())->count() }}</span>
                <span class="stat-label">Alertes stock bas</span>
            </div>
        </div>

        <div class="stat-card-horizontal">
            <div class="stat-icon-container green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ \App\Models\Categorie::where('boutique_id', auth()->user()->boutique_id)->count() }}</span>
                <span class="stat-label">Catégories</span>
            </div>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="filter-row" style="display: flex; gap: 12px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
        <div style="position: relative; flex: 1; min-width: 240px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Rechercher un produit..." class="form-input" style="padding-left: 36px; height: 40px; margin-bottom: 0;">
        </div>
        
        <select id="category-filter" class="form-input" style="width: 200px; height: 40px; margin-bottom: 0;">
            <option value="all">Toutes les catégories</option>
            @foreach(\App\Models\Categorie::where('boutique_id', auth()->user()->boutique_id)->get() as $categorie)
                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
            @endforeach
        </select>

        <button type="button" id="stock-alert-toggle" class="btn btn-secondary" style="height: 40px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: var(--red);"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Stock bas seulement
        </button>

        <button type="button" id="filter-btn" class="btn btn-secondary" style="height: 40px; display: inline-flex; align-items: center; gap: 6px;">
            Filtrer
        </button>
    </div>

    @if($produits->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z"/></svg>
            <h3>Aucun produit pour le moment</h3>
            <p>Ajoutez votre premier produit pour commencer à gérer votre stock.</p>
            <a href="{{ route('produits.create') }}" class="btn-action">Ajouter un produit</a>
        </div>
    @else
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-light); background: var(--card);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Catalogue produits</h3>
                <span style="font-size: 13px; color: var(--text-sec);" id="products-count">{{ $produits->count() }} produit(s)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Produit</th>
                        <th>Catégorie</th>
                        <th>Prix achat</th>
                        <th>Prix vente</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produits as $produit)
                        <tr data-nom="{{ strtolower($produit->nom) }}" data-code="{{ strtolower($produit->code) }}" data-categorie="{{ $produit->categorie_id }}" data-stock-critique="{{ $produit->estEnStockCritique() ? '1' : '0' }}">
                            <td class="text-muted">{{ $produit->code }}</td>
                            <td class="font-medium">{{ $produit->nom }}</td>
                            <td>
                                @if($produit->categorie)
                                    <span class="badge-category">{{ $produit->categorie->nom }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ number_format($produit->prix_achat, 0, ',', ' ') }} F</td>
                            <td>{{ number_format($produit->prix_vente, 0, ',', ' ') }} F</td>
                            <td>
                                <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
                                    <span class="font-medium" style="color: {{ $produit->estEnStockCritique() ? 'var(--red)' : 'var(--green)' }}; font-weight: 700;">{{ $produit->quantite_stock }}</span>
                                    <div style="width: 40px; height: 3px; background: {{ $produit->estEnStockCritique() ? 'var(--red)' : 'var(--green)' }}; border-radius: 2px;"></div>
                                </div>
                            </td>
                            <td>
                                @if($produit->quantite_stock <= 0)
                                    <span class="badge-status warning">Rupture</span>
                                @elseif($produit->estEnStockCritique())
                                    <span class="badge-status warning">Stock Bas</span>
                                @else
                                    <span class="badge-status active">Actif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="row-actions">
                                    <a href="{{ route('produits.edit', $produit) }}" class="icon-btn-sm" title="Modifier">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('produits.destroy', $produit) }}" onsubmit="return confirm('Supprimer ce produit ?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn-sm icon-btn-danger" title="Supprimer">
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

    <!-- Client-side filters JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const stockAlertToggle = document.getElementById('stock-alert-toggle');
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            const countSpan = document.getElementById('products-count');
            let showOnlyAlerts = false;

            // Toggle stock alert filter
            stockAlertToggle.addEventListener('click', function() {
                showOnlyAlerts = !showOnlyAlerts;
                if (showOnlyAlerts) {
                    stockAlertToggle.style.background = 'var(--red-light)';
                    stockAlertToggle.style.borderColor = 'var(--red)';
                    stockAlertToggle.style.color = 'var(--red)';
                } else {
                    stockAlertToggle.style.background = '';
                    stockAlertToggle.style.borderColor = '';
                    stockAlertToggle.style.color = '';
                }
                applyFilters();
            });

            // Input listeners
            searchInput.addEventListener('input', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value;
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const nom = row.getAttribute('data-nom') || '';
                    const code = row.getAttribute('data-code') || '';
                    const catId = row.getAttribute('data-categorie') || '';
                    const isCritique = row.getAttribute('data-stock-critique') === '1';

                    const matchesSearch = nom.includes(query) || code.includes(query);
                    const matchesCategory = selectedCategory === 'all' || catId === selectedCategory;
                    const matchesAlert = !showOnlyAlerts || isCritique;

                    if (matchesSearch && matchesCategory && matchesAlert) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (countSpan) {
                    countSpan.innerText = visibleCount + ' produit(s)';
                }
            }
        });
    </script>

</x-app-layout>