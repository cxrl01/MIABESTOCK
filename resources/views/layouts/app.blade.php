<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - MiabéStock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo-badge">
                @if(auth()->user()->boutique?->logo)
                    <img src="{{ asset('storage/' . auth()->user()->boutique->logo) }}" alt="Logo boutique">
                @else
                    {{ substr(auth()->user()->boutique->nom ?? 'B', 0, 1) }}
                @endif
            </div>
            <div>
                <div class="sidebar-boutique-nom">{{ auth()->user()->boutique->nom ?? 'Ma Boutique' }}</div>
                <div class="sidebar-boutique-sub">Gestion Commerciale</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu principal</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </span>
            </a>

            <a href="{{ route('ventes.index') }}" class="nav-item {{ request()->routeIs('ventes.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    Ventes
                </span>
            </a>

            <a href="{{ route('produits.index') }}"
                class="nav-item {{ request()->routeIs('produits.*') || request()->routeIs('categories.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                        <path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z" />
                    </svg>
                    Stocks & Produits
                </span>
            </a>

            <a href="{{ route('clients.index') }}"
                class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Clients
                </span>
            </a>

            <a href="{{ route('fournisseurs.index') }}"
    class="nav-item {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
    <span class="nav-item-left">
        <!-- Icône de colis / livraison -->
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
            <line x1="12" y1="22.08" x2="12" y2="12"></line>
        </svg>
        Fournisseurs
    </span>
</a>

            <a href="{{ route('livraisons.index') }}"
                class="nav-item {{ request()->routeIs('livraisons.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                    Livraisons
                </span>
            </a>

            @if(auth()->user()->estGerant())
                <a href="{{ route('equipe.index') }}" class="nav-item {{ request()->routeIs('equipe.*') ? 'active' : '' }}">
                    <span class="nav-item-left">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        Équipe
                    </span>
                </a>
            @endif

            <a href="{{ route('depenses.index') }}"
                class="nav-item {{ request()->routeIs('depenses.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    Dépenses
                </span>
            </a>

            <a href="{{ route('rapports.index') }}"
                class="nav-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    Rapports & Stats
                </span>
            </a>

            @if(auth()->user()->estGerant())
                <a href="{{ route('administration.edit') }}"
                    class="nav-item {{ request()->routeIs('administration.*') ? 'active' : '' }}">
                    <span class="nav-item-left">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.07 4.93A10 10 0 0 1 21 12a10 10 0 0 1-1.93 7.07M4.93 4.93A10 10 0 0 0 3 12a10 10 0 0 0 1.93 7.07" />
                        </svg>
                        Administration
                    </span>
                </a>
            @endif

            <div class="nav-section-label">Compte</div>

            <a href="{{ route('profile.edit') }}"
                class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Profil
                </span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="nav-item-form">
                @csrf
                <button type="submit" class="nav-item nav-item-button">
                    <span class="nav-item-left">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Déconnexion
                    </span>
                </button>
            </form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN AREA -->
    <div class="main-area" id="mainArea">

        <!-- TOPBAR -->
        <header class="topbar">
            <button class="icon-btn" id="sidebarToggle" title="Afficher/Masquer le menu">
                <svg viewBox="0 0 24 24">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>

            <div class="topbar-title">
                <h1>{{ $title ?? 'Dashboard' }}</h1>
                <div class="topbar-breadcrumb">
                    Accueil / <span>{{ $title ?? 'Dashboard' }}</span>
                </div>
            </div>
            <div class="topbar-actions">
                <div class="topbar-date" id="currentDate"></div>

                <button class="icon-btn theme-btn" title="Changer le thème" onclick="toggleTheme()">
                    <svg class="icon-sun" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" />
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </svg>
                    <svg class="icon-moon" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                </button>

                <!-- <button class="icon-btn" title="Notifications">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                </button> -->
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="page-content">

            @if(session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>

    </div>

    <script>
        // Date dynamique
        const d = new Date();
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = d.toLocaleDateString('fr-FR', opts);

        // Thème : charger depuis localStorage
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

        // Ouvrir / fermer la sidebar
        const sidebar = document.getElementById('sidebar');
        const mainArea = document.getElementById('mainArea');
        const toggleBtn = document.getElementById('sidebarToggle');

        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed) {
            sidebar.classList.add('sidebar-collapsed');
            mainArea.classList.add('main-area-expanded');
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            mainArea.classList.toggle('main-area-expanded');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
        });
    </script>

</body>

</html>