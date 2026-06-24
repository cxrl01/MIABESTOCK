<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MiabéStock — Gestion commerciale</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<!-- DECORATIONS -->
<div class="glow-circle glow-1"></div>
<div class="glow-circle glow-2"></div>
 
<!-- NAV -->
<nav>
  <div class="nav-logo">Miabé<span>Stock</span></div>
  <div class="nav-actions">
    <a href="{{ route('login') }}" class="btn btn-ghost">Connexion</a>
    <a href="{{ route('register') }}" class="btn btn-solid">Créer ma boutique</a>
  </div>
</nav>
 
<!-- HERO -->
<section class="hero">
  <div class="hero-eyebrow">Gestion commerciale intelligente</div>
  <h1>Votre boutique,<br><em>pilotée avec précision.</em></h1>
  <p class="hero-sub">Stock, ventes, dettes clients et rapports financiers — tout en un seul endroit, pour les commerçants qui veulent vraiment garder le contrôle.</p>
  <div class="hero-cta">
    <a href="{{ route('register') }}" class="btn btn-solid btn-lg">Commencer gratuitement</a>
    <a href="#features" class="btn btn-ghost btn-lg">Voir les fonctionnalités</a>
  </div>
</section>
 
<!-- TRUST BAR -->
<div class="trust">
  <div class="trust-inner">
    <div class="trust-item">
      <div class="trust-icon">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      Données sécurisées
    </div>
    <div class="trust-item">
      <div class="trust-icon">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      Multi-boutiques
    </div>
    <div class="trust-item">
      <div class="trust-icon">
        <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      Temps réel
    </div>
    <div class="trust-item">
      <div class="trust-icon">
        <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      </div>
      Web & mobile
    </div>
  </div>
</div>
 
<!-- DASHBOARD PREVIEW -->
<section class="preview-section">
  <div class="preview-header">
    <div class="section-label">Interface</div>
    <h2 class="section-title">Tout ce qu'il vous faut, d'un coup d'œil</h2>
  </div>
  <div class="preview-wrap">
    <div class="preview-bar">
      <div class="dot dot-r"></div>
      <div class="dot dot-y"></div>
      <div class="dot dot-g"></div>
      <div class="preview-url">app.miabestock.com/dashboard</div>
    </div>
    <div class="dashboard">
      <div class="dash-sidebar">
        <div class="dash-logo">MiabéStock</div>
        <div class="dash-nav-item active">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          Tableau de bord
        </div>
        <div class="dash-nav-item">
          <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          Stocks
        </div>
        <div class="dash-nav-item">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          Ventes
        </div>
        <div class="dash-nav-item">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          Clients
        </div>
        <div class="dash-nav-item">
          <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Trésorerie
        </div>
        <div class="dash-nav-item">
          <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Rapports
        </div>
      </div>
      <div class="dash-main">
        <div class="dash-header">Bonjour, Kofi 👋 — Voici votre journée</div>
        <div class="kpi-row">
          <div class="kpi">
            <div class="kpi-label">Ventes du jour</div>
            <div class="kpi-val">124 500 F</div>
            <span class="kpi-badge badge-up">+12%</span>
          </div>
          <div class="kpi">
            <div class="kpi-label">Produits en stock</div>
            <div class="kpi-val">347</div>
            <span class="kpi-badge badge-down">3 alertes</span>
          </div>
          <div class="kpi">
            <div class="kpi-label">Dettes clients</div>
            <div class="kpi-val">89 200 F</div>
            <span class="kpi-badge badge-down">5 impayés</span>
          </div>
          <div class="kpi">
            <div class="kpi-label">Marge nette</div>
            <div class="kpi-val">31 %</div>
            <span class="kpi-badge badge-up">+3 pts</span>
          </div>
        </div>
        <div class="chart-row">
          <div class="chart-box">
            <div class="chart-title">Ventes — 6 derniers mois</div>
            <div class="bars">
              <div class="bar" style="height:40%"></div>
              <div class="bar" style="height:55%"></div>
              <div class="bar" style="height:45%"></div>
              <div class="bar" style="height:70%"></div>
              <div class="bar" style="height:60%"></div>
              <div class="bar active" style="height:85%"></div>
            </div>
            <div class="months">
              <span class="month">Jan</span>
              <span class="month">Fév</span>
              <span class="month">Mar</span>
              <span class="month">Avr</span>
              <span class="month">Mai</span>
              <span class="month">Juin</span>
            </div>
          </div>
          <div class="chart-box">
            <div class="chart-title">Top catégories</div>
            <div class="pie-wrap">
              <svg width="80" height="80" viewBox="0 0 36 36">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#1a56db" stroke-width="3" stroke-dasharray="45 55" stroke-dashoffset="25" transform="rotate(-90 18 18)"/>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#60a5fa" stroke-width="3" stroke-dasharray="28 72" stroke-dashoffset="-20" transform="rotate(-90 18 18)"/>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#bfdbfe" stroke-width="3" stroke-dasharray="27 73" stroke-dashoffset="-48" transform="rotate(-90 18 18)"/>
              </svg>
              <div class="pie-legend">
                <div class="legend-item"><div class="legend-dot" style="background:#1a56db"></div>Alimentation 45%</div>
                <div class="legend-item"><div class="legend-dot" style="background:#60a5fa"></div>Boissons 28%</div>
                <div class="legend-item"><div class="legend-dot" style="background:#bfdbfe"></div>Autres 27%</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
 
<!-- FEATURES -->
<section id="features" class="section">
  <div class="section-label">Fonctionnalités</div>
  <h2 class="section-title">Tout ce dont votre commerce a besoin</h2>
  <p class="section-sub">Six modules pensés pour s'adapter à votre façon de travailler, pas l'inverse.</p>
  <div class="features-grid">
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      </div>
      <h3>Point de vente rapide</h3>
      <p>Créez une vente en quelques secondes. Calculez le total automatiquement, choisissez le mode de paiement et générez la facture instantanément.</p>
    </div>
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </div>
      <h3>Gestion des stocks</h3>
      <p>Suivez chaque produit en temps réel. Recevez une alerte automatique dès qu'un article approche de son seuil critique, avant la rupture.</p>
    </div>
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <h3>Suivi des dettes</h3>
      <p>Chaque paiement partiel génère sa propre facture numérotée. Retrouvez l'historique complet de chaque client en cas de litige.</p>
    </div>
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <h3>Trésorerie en temps réel</h3>
      <p>Entrées, sorties, dépenses courantes : visualisez votre solde exact à tout moment, sans attendre la fin du mois.</p>
    </div>
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </div>
      <h3>Rapports & statistiques</h3>
      <p>Chiffre d'affaires, marges, top ventes : prenez des décisions basées sur vos vraies données, exportables en PDF.</p>
    </div>
    <div class="feat">
      <div class="feat-icon">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <h3>Équipe & rôles</h3>
      <p>Le Gérant crée et gère les comptes Gestionnaire et Commercial. Chacun accède uniquement à ce qui le concerne.</p>
    </div>
  </div>
</section>
 
<!-- HOW IT WORKS -->
<section class="section section-surface">
  <div class="section-center">
    <div class="section-label">Comment ça marche</div>
    <h2 class="section-title">Opérationnel en 3 étapes</h2>
  </div>
  <div class="how-grid">
    <div class="step">
      <div class="step-num">01</div>
      <h3>Créez votre boutique</h3>
      <p>Inscrivez-vous, configurez le nom, la devise et le logo de votre boutique en moins de 2 minutes.</p>
    </div>
    <div class="step">
      <div class="step-num">02</div>
      <h3>Ajoutez vos produits</h3>
      <p>Importez votre catalogue, définissez les prix d'achat et de vente, et paramétrez les seuils d'alerte.</p>
    </div>
    <div class="step">
      <div class="step-num">03</div>
      <h3>Vendez et suivez</h3>
      <p>Enregistrez vos ventes, gérez vos clients et consultez vos rapports depuis n'importe quel appareil.</p>
    </div>
  </div>
</section>
 
<!-- PRICING -->
<section class="section section-center-all">
  <div class="section-label">Tarifs</div>
  <h2 class="section-title">Simple et transparent</h2>
  <p class="section-sub" style="margin:0 auto 52px">Commencez gratuitement. Évoluez quand vous en avez besoin.</p>
  <div class="pricing-grid">
    <div class="plan">
      <div class="plan-name">Starter</div>
      <div class="plan-price">Gratuit</div>
      <div class="plan-desc">Pour démarrer et tester la plateforme sans engagement.</div>
      <ul class="plan-features">
        <li>1 boutique</li>
        <li>Jusqu'à 100 produits</li>
        <li>Gestion des ventes</li>
        <li>Suivi des dettes</li>
      </ul>
      <a href="{{ route('register') }}" class="btn btn-ghost btn-full">Commencer</a>
    </div>
    <div class="plan plan-featured">
      <div class="plan-badge">Le plus choisi</div>
      <div class="plan-name">Pro</div>
      <div class="plan-price">9 900 F<span>/mois</span></div>
      <div class="plan-desc">Pour les boutiques actives qui veulent tout contrôler.</div>
      <ul class="plan-features">
        <li>Boutiques illimitées</li>
        <li>Produits illimités</li>
        <li>Gestion d'équipe complète</li>
        <li>Rapports & exports PDF</li>
        <li>Alertes automatiques</li>
        <li>Support prioritaire</li>
      </ul>
      <a href="{{ route('register') }}" class="btn btn-solid btn-full">Essayer 14 jours gratuit</a>
    </div>
  </div>
</section>
 
<!-- FAQ -->
<section class="section section-surface">
  <div class="section-center" style="margin-bottom:48px">
    <div class="section-label">FAQ</div>
    <h2 class="section-title">Questions fréquentes</h2>
  </div>
  <div class="faq-list">
    <details>
      <summary>MiabéStock fonctionne-t-il sans connexion internet stable ?</summary>
      <div class="faq-body">L'application est conçue pour être légère et rapide, même avec une connexion limitée. Les données sont synchronisées dès que la connexion est rétablie.</div>
    </details>
    <details>
      <summary>Qui peut créer des comptes Gestionnaire ou Commercial ?</summary>
      <div class="faq-body">Seul le Gérant, propriétaire de la boutique, peut créer et gérer les comptes de son équipe. Chaque employé accède uniquement aux modules qui lui sont attribués.</div>
    </details>
    <details>
      <summary>Comment fonctionne la facturation des dettes ?</summary>
      <div class="faq-body">Chaque paiement reçu génère automatiquement une facture distincte avec un numéro unique. Vous pouvez retrouver l'historique complet de chaque client en cas de litige.</div>
    </details>
    <details>
      <summary>Mes données sont-elles isolées des autres boutiques ?</summary>
      <div class="faq-body">Oui. Chaque boutique dispose de son espace entièrement isolé. Aucun autre utilisateur externe ne peut accéder à vos données.</div>
    </details>
  </div>
</section>
 
<!-- CTA FINAL -->
<section class="cta-section">
  <h2>Prenez le contrôle de votre commerce dès aujourd'hui</h2>
  <p>Rejoignez les commerçants qui pilotent leur activité avec précision — sans complexité inutile.</p>
  <a href="{{ route('register') }}" class="btn-cta">Créer ma boutique gratuitement</a>
</section>
 
<!-- FOOTER -->
<footer>
  <div class="footer-logo">MiabéStock</div>
  <p style="font-size:13px">© 2026 MiabéStock — Tous droits réservés</p>
</footer>
 
</body>
</html>