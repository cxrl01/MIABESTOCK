<x-app-layout title="Clients">

    <div class="page-header">
        <div>
            <h2 class="page-header-title">Clients</h2>
            <p class="page-header-sub">{{ $clients->count() }} client(s) enregistré(s)</p>
        </div>
        <a href="{{ route('clients.create') }}" class="btn-action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px; margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau client
        </a>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card-horizontal">
            <div class="stat-icon-container accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number">{{ $clients->count() }}</span>
                <span class="stat-label">clients enregistrés</span>
            </div>
        </div>

        <div class="stat-card-horizontal">
            <div class="stat-icon-container red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number" style="color: var(--red);">{{ $clients->filter(fn($c) => $c->solde_dette > 0)->count() }}</span>
                <span class="stat-label">client(s) en impayé</span>
            </div>
        </div>

        <div class="stat-card-horizontal">
            <div class="stat-icon-container red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div class="stat-details">
                <span class="stat-number" style="color: var(--red);">{{ number_format($clients->sum('solde_dette'), 0, ',', ' ') }} F</span>
                <span class="stat-label">montant total impayé</span>
            </div>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="filter-row" style="display: flex; gap: 12px; margin-bottom: 20px; align-items: center; flex-wrap: wrap;">
        <div style="position: relative; flex: 1; min-width: 240px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Rechercher un client..." class="form-input" style="padding-left: 36px; height: 40px; margin-bottom: 0;">
        </div>
        
        <select id="status-filter" class="form-input" style="width: 200px; height: 40px; margin-bottom: 0;">
            <option value="all">Tous les statuts</option>
            <option value="active">Actif</option>
            <option value="unpaid">Impayé</option>
            <option value="inactive">Inactif</option>
        </select>

        <select id="clients-filter" class="form-input" style="width: 200px; height: 40px; margin-bottom: 0;">
            <option value="all">Tous les clients</option>
            <option value="has-debt">Avec dette seulement</option>
            <option value="no-debt">Sans dette seulement</option>
        </select>

        <button type="button" id="filter-btn" class="btn btn-secondary" style="height: 40px; display: inline-flex; align-items: center; gap: 6px;">
            Filtrer
        </button>
    </div>

    @if($clients->isEmpty())
        <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <h3>Aucun client pour le moment</h3>
            <p>Ajoutez votre premier client pour commencer à suivre ses achats.</p>
            <a href="{{ route('clients.create') }}" class="btn-action">Ajouter un client</a>
        </div>
    @else
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border-light); background: var(--card);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text);">Liste des clients</h3>
                <span style="font-size: 13px; color: var(--text-sec);" id="clients-count">{{ $clients->count() }} client(s)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Dette</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        @php
                            $statusText = 'Actif';
                            $statusClass = 'active';
                            if ($client->solde_dette > 0) {
                                $statusText = 'Impayé';
                                $statusClass = 'warning';
                            } elseif ($client->commandes_count == 0) {
                                $statusText = 'Inactif';
                                $statusClass = 'inactive';
                            }
                        @endphp
                        <tr data-nom="{{ strtolower($client->nom_complet) }}" 
                            data-phone="{{ $client->telephone }}" 
                            data-email="{{ $client->email }}"
                            data-adresse="{{ $client->adresse }}"
                            data-dette="{{ number_format($client->solde_dette, 0, ',', ' ') }} F"
                            data-commandes="{{ $client->commandes_count }}"
                            data-status-text="{{ $statusText }}"
                            data-status-class="{{ $statusClass }}"
                            data-has-debt="{{ $client->solde_dette > 0 ? '1' : '0' }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar-circle">
                                        {{ substr($client->nom_complet, 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ $client->nom_complet }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $client->telephone ?? '—' }}</td>
                            <td class="text-muted">{{ $client->email ?? '—' }}</td>
                            <td>
                                @if($client->solde_dette > 0)
                                    <span style="color: var(--red); font-weight: 700;">{{ number_format($client->solde_dette, 0, ',', ' ') }} F</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="text-right">
                                <div class="row-actions" style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Voir
                                    </a>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; height: 32px; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Supprimer ce client ?');" style="display: inline;">
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

    <!-- Modal de détails client -->
    <div id="client-modal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div class="modal-content" style="background-color: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; max-width: 480px; width: 90%; box-shadow: var(--shadow); position: relative; margin: auto;">
            <button type="button" id="close-modal" class="icon-btn-sm" style="position: absolute; right: 16px; top: 16px; cursor: pointer; border: none; background: none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
                <div id="modal-avatar" class="avatar-circle" style="width: 48px; height: 48px; font-size: 20px; border-radius: 12px;">A</div>
                <div>
                    <h3 id="modal-name" style="font-size: 18px; font-weight: 700; color: var(--text);">Nom du Client</h3>
                    <span id="modal-status" class="badge-status active" style="margin-top: 4px; font-size: 11px;">Actif</span>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13.5px;">
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700;">Téléphone</span>
                    <span id="modal-phone" style="font-weight: 500;">—</span>
                </div>
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700;">Email</span>
                    <span id="modal-email" style="font-weight: 500;">—</span>
                </div>
                <div>
                    <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700;">Adresse</span>
                    <span id="modal-address" style="font-weight: 500;">—</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; border-top: 1px solid var(--border-light); padding-top: 16px; margin-top: 6px;">
                    <div>
                        <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700;">Commandes</span>
                        <span id="modal-commands" style="font-size: 16px; font-weight: 700;">0</span>
                    </div>
                    <div>
                        <span style="color: var(--text-sec); display: block; font-size: 11px; text-transform: uppercase; font-weight: 700;">Solde Dette</span>
                        <span id="modal-debt" style="font-size: 16px; font-weight: 700; color: var(--red);">0 F</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side script for filters and details modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live filters elements
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const clientsFilter = document.getElementById('clients-filter');
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            const countSpan = document.getElementById('clients-count');

            // Modal elements
            const modal = document.getElementById('client-modal');
            const closeModal = document.getElementById('close-modal');

            // Apply filters event listeners
            searchInput.addEventListener('input', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            clientsFilter.addEventListener('change', applyFilters);

            function applyFilters() {
                const query = searchInput.value.toLowerCase().trim();
                const selectedStatus = statusFilter.value;
                const selectedClientFilter = clientsFilter.value;
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const nom = row.getAttribute('data-nom') || '';
                    const phone = row.getAttribute('data-phone') || '';
                    const email = row.getAttribute('data-email') || '';
                    const statusClass = row.getAttribute('data-status-class') || '';
                    const hasDebt = row.getAttribute('data-has-debt') === '1';

                    const matchesSearch = nom.includes(query) || phone.includes(query) || email.includes(query);
                    
                    let matchesStatus = true;
                    if (selectedStatus === 'active') matchesStatus = statusClass === 'active';
                    else if (selectedStatus === 'unpaid') matchesStatus = statusClass === 'warning';
                    else if (selectedStatus === 'inactive') matchesStatus = statusClass === 'inactive';

                    let matchesClient = true;
                    if (selectedClientFilter === 'has-debt') matchesClient = hasDebt;
                    else if (selectedClientFilter === 'no-debt') matchesClient = !hasDebt;

                    if (matchesSearch && matchesStatus && matchesClient) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (countSpan) {
                    countSpan.innerText = visibleCount + ' client(s)';
                }
            }

            // View Details Modal functionality
            document.querySelectorAll('.view-client-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const name = row.getAttribute('data-nom');
                    const phone = row.getAttribute('data-phone') || '—';
                    const email = row.getAttribute('data-email') || '—';
                    const address = row.getAttribute('data-adresse') || '—';
                    const debt = row.getAttribute('data-dette') || '0 F';
                    const commands = row.getAttribute('data-commandes') || '0';
                    const status = row.getAttribute('data-status-text') || 'Actif';
                    const statusClass = row.getAttribute('data-status-class') || 'active';

                    // Capitalize first letter of words in name for presentation
                    const formattedName = name.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());

                    document.getElementById('modal-name').innerText = formattedName;
                    document.getElementById('modal-avatar').innerText = formattedName.charAt(0);
                    document.getElementById('modal-phone').innerText = phone;
                    document.getElementById('modal-email').innerText = email;
                    document.getElementById('modal-address').innerText = address;
                    document.getElementById('modal-commands').innerText = commands;
                    document.getElementById('modal-debt').innerText = debt;
                    
                    const statusEl = document.getElementById('modal-status');
                    statusEl.innerText = status;
                    statusEl.className = 'badge-status ' + statusClass;

                    modal.style.display = 'flex';
                });
            });

            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', e => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

</x-app-layout>