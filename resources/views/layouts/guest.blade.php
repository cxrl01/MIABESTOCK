<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiabéStock') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; min-height: 100vh; overflow-x: hidden; }
        
        .auth-wrapper { 
            display: flex; 
            min-height: 100vh; 
            position: relative;
        }

        /* PANNEAU GAUCHE - Premium immersive dark styling */
        .auth-left {
            flex: 1.2;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: radial-gradient(circle at 0% 0%, #1e1b4b 0%, #0f172a 100%);
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Decorative blur effects */
        .auth-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(99, 102, 241, 0.2);
            filter: blur(100px);
            border-radius: 50%;
            top: -50px;
            left: -50px;
            pointer-events: none;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(16, 185, 129, 0.15);
            filter: blur(100px);
            border-radius: 50%;
            bottom: -50px;
            right: -50px;
            pointer-events: none;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10;
        }
        .auth-logo-icon {
            width: 44px; height: 44px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .auth-logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .auth-logo-text span { color: #6366f1; }

        .auth-headline-container {
            z-index: 10;
            margin: 60px 0;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .auth-headline { display: flex; flex-direction: column; gap: 16px; }
        .auth-eyebrow {
            font-size: 12px; font-weight: 800;
            letter-spacing: 3px; text-transform: uppercase;
            color: #6366f1;
        }
        .auth-title {
            font-family: 'Syne', sans-serif;
            font-size: 38px; font-weight: 800;
            color: #fff; line-height: 1.2;
            letter-spacing: -1px;
        }
        .auth-desc {
            font-size: 15.5px;
            color: #94a3b8;
            line-height: 1.7;
            max-width: 420px;
        }

        /* Glassmorphic feature card */
        .auth-features-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 24px 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            max-width: 440px;
        }

        .auth-feature { display: flex; align-items: center; gap: 14px; }
        .auth-feature-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.25);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .auth-feature-icon svg {
            width: 18px; height: 18px;
            stroke: #a5b4fc; fill: none;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }
        .auth-feature span { font-size: 14px; color: #e2e8f0; font-weight: 500; }

        .auth-footer-left {
            z-index: 10;
            font-size: 13px;
            color: #64748b;
        }

        /* PANNEAU DROIT - Clean form styling with high-end focus */
        .auth-right {
            width: 540px;
            flex-shrink: 0;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px 64px;
            box-shadow: -10px 0 40px rgba(0,0,0,0.15);
            position: relative;
            z-index: 10;
        }

        .auth-form-body {
            margin: auto 0;
        }

        /* Custom Modern Forms Styles */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            color: #0f172a;
            font-size: 14.5px;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            margin-top: 6px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        label {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            display: block;
        }

        /* Modern styled primary button component override */
        button[type="submit"],
        .btn-auth-primary {
            width: 100%;
            padding: 13px 24px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        button[type="submit"]:hover,
        .btn-auth-primary:hover {
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .auth-left { padding: 48px 56px; }
            .auth-right { width: 480px; padding: 48px; }
            .auth-title { font-size: 32px; }
        }

        @media (max-width: 868px) {
            .auth-left { display: none; }
            .auth-right { width: 100%; min-height: 100vh; padding: 48px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- PANNEAU GAUCHE -->
    <div class="auth-left">

        <div class="auth-logo">
            <div class="auth-logo-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
            </div>
            <span class="auth-logo-text">Miabé<span>Stock</span></span>
        </div>

        <div class="auth-headline-container">
            <div class="auth-headline">
                <p class="auth-eyebrow">MiabéStock Enterprise</p>
                <h2 class="auth-title">Prenez le contrôle de votre boutique en temps réel.</h2>
                <p class="auth-desc">Stockage cloud hautement sécurisé, suivi des dettes clients, gestion d'équipe et facturation en une plateforme unique.</p>
            </div>

            <!-- Modern Feature Glass Card -->
            <div class="auth-features-card">
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <span>Analytics & Graphiques de Vente</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span>Reçus et Factures imprimables</span>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <span>Contrôle d'accès & Rôles Employés</span>
                </div>
            </div>
        </div>

        <div class="auth-footer-left">
            <span>Propulsé par Google Cloud & Heroku</span>
        </div>

    </div>

    <!-- PANNEAU DROIT -->
    <div class="auth-right">
        <div></div> <!-- spacer -->
        
        <div class="auth-form-body">
            {{ $slot }}
        </div>
        
        <p style="text-align:center; font-size:12.5px; color:#94a3b8; font-weight:500;">© 2026 MiabéStock — Tous droits réservés</p>
    </div>

</div>

</body>
</html>