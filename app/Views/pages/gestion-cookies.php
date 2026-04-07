<section class="legal-page cookie-page">
  <div class="legal-container cookie-page__container">
    <h1>Gestion des cookies</h1>

    <p class="legal-intro">
      Cette page vous permet de gérer vos préférences de consentement à tout moment.
      Les cookies nécessaires restent actifs pour garantir le bon fonctionnement du site.
    </p>

    <h2>Categories de cookies</h2>
    <ul>
      <li><strong>Nécessaires :</strong> indispensables au fonctionnement technique du site.</li>
      <li><strong>Analytiques :</strong> permettent de mesurer l'audience et d'ameliorer le contenu.</li>
      <li><strong>Marketing :</strong> permettent des mesures publicitaires et du ciblage.</li>
    </ul>

    <div class="cookie-page__panel">
      <label class="cookie-switch">
        <span>Cookies necessaires (toujours actifs)</span>
        <input type="checkbox" checked disabled>
      </label>

      <label class="cookie-switch">
        <span>Cookies analytiques</span>
        <input type="checkbox" id="cookie-page-analytics-toggle">
      </label>

      <label class="cookie-switch">
        <span>Cookies marketing</span>
        <input type="checkbox" id="cookie-page-marketing-toggle">
      </label>
    </div>

    <div class="cookie-page__actions">
      <button type="button" class="cookie-btn cookie-btn--primary" id="cookie-page-accept-all">Activer tout</button>
      <button type="button" class="cookie-btn cookie-btn--outline" id="cookie-page-reject-all">Refuser tout</button>
      <button type="button" class="cookie-btn cookie-btn--ghost" id="cookie-page-save">Sauvegarder preferences</button>
    </div>

    <p class="cookie-page__status" id="cookie-page-status" aria-live="polite"></p>
  </div>
</section>

<script>
  (function cookieSettingsPage() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', cookieSettingsPage);
      return;
    }

    if (!window.cookieConsent) return;

    var analytics = document.getElementById('cookie-page-analytics-toggle');
    var marketing = document.getElementById('cookie-page-marketing-toggle');
    var status = document.getElementById('cookie-page-status');

    function readState() {
      var consent = window.cookieConsent.read() || {
        analytics: false,
        marketing: false
      };
      if (analytics) analytics.checked = !!consent.analytics;
      if (marketing) marketing.checked = !!consent.marketing;
    }

    function setMessage(text) {
      if (status) status.textContent = text;
    }

    document.getElementById('cookie-page-accept-all')?.addEventListener('click', function() {
      window.cookieConsent.acceptAll();
      readState();
      setMessage('Tous les cookies optionnels sont actives.');
    });

    document.getElementById('cookie-page-reject-all')?.addEventListener('click', function() {
      window.cookieConsent.rejectAll();
      readState();
      setMessage('Tous les cookies optionnels sont desactives.');
    });

    document.getElementById('cookie-page-save')?.addEventListener('click', function() {
      window.cookieConsent.savePreferences({
        analytics: !!analytics?.checked,
        marketing: !!marketing?.checked
      });
      readState();
      setMessage('Vos préférences cookies ont été enregistrées.');
    });

    window.addEventListener('cookie-consent-updated', readState);
    readState();
  })();
</script>