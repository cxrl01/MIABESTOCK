<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Administration' }} - MiabéStock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo-badge" style="background: #1f2937;">SA</div>
            <div>
                <div class="sidebar-boutique-nom">Super Admin</div>
                <div class="sidebar-boutique-sub">MiabéStock — Supervision</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Supervision</div>

            <a href="{{ route('admin.boutiques.index') }}" class="nav-item {{ request()->routeIs('admin.boutiques.*') ? 'active' : '' }}">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
                    Boutiques
                </span>
            </a>

            <a href="#" class="nav-item">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Statistiques globales
                </span>
            </a>

            <a href="#" class="nav-item">
                <span class="nav-item-left">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Journal d'activité
                </span>
            </a>

            <div class="nav-section-label">Compte</div>

<a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="nav-item-left">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profil
    </span>
</a>

<form method="POST" action="{{ route('logout') }}" class="nav-item-form">
    @csrf
    <button type="submit" class="nav-item nav-item-button">
        <span class="nav-item-left">
            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Déconnexion
        </span>
    </button>
</form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar" style="background: #1f2937;">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Super Admin</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN AREA -->
    <div class="main-area" id="mainArea">

        <!-- TOPBAR -->
        <header class="topbar">
            <button class="icon-btn" id="sidebarToggle" title="Afficher/Masquer le menu">
                <svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            <div class="topbar-title">
                <h1>{{ $title ?? 'Administration' }}</h1>
                <div class="topbar-breadcrumb">
                    Super Admin / <span>{{ $title ?? 'Administration' }}</span>
                </div>
            </div>
            <div class="topbar-actions">
                <div class="topbar-date" id="currentDate"></div>

                <button class="icon-btn theme-btn" title="Changer le thème" onclick="toggleTheme()">
                    <svg class="icon-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="page-content">

            @if(session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>

    </div>

    <script>
        const d = new Date();
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = d.toLocaleDateString('fr-FR', opts);

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

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