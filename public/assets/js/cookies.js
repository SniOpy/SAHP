/* =====================================================
   Gestion simple du consentement cookies (RGPD)
   - Stockage localStorage (6 mois)
   - Compatible Consent Mode Google
   - Activation differree des scripts non essentiels
===================================================== */
(function cookieConsentManager() {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', cookieConsentManager);
    return;
  }

  const STORAGE_KEY = 'sahp_cookie_consent';
  const COOKIE_NAME = 'sahp_cookie_consent';
  const CONSENT_DURATION_MS = 180 * 24 * 60 * 60 * 1000; // 6 mois

  const banner = document.getElementById('cookie-banner');
  const modal = document.getElementById('cookie-modal');
  const analyticsToggle = document.getElementById('cookie-analytics-toggle');
  const marketingToggle = document.getElementById('cookie-marketing-toggle');

  function now() {
    return Date.now();
  }

  function getDefaultConsent() {
    return {
      necessary: true,
      analytics: false,
      marketing: false
    };
  }

  function writeConsentCookie(payload) {
    try {
      const expiresDate = new Date(now() + CONSENT_DURATION_MS).toUTCString();
      const encodedValue = encodeURIComponent(JSON.stringify(payload));
      const secureFlag = window.location.protocol === 'https:' ? '; Secure' : '';
      document.cookie = COOKIE_NAME + '=' + encodedValue
        + '; expires=' + expiresDate
        + '; path=/; SameSite=Lax'
        + secureFlag;
    } catch (error) {
      // Ignore cookie write errors (fallback localStorage)
    }
  }

  function readConsentCookie() {
    try {
      const cookies = document.cookie ? document.cookie.split('; ') : [];
      const cookiePrefix = COOKIE_NAME + '=';
      const cookieRow = cookies.find((row) => row.indexOf(cookiePrefix) === 0);
      if (!cookieRow) return null;
      const rawValue = cookieRow.substring(cookiePrefix.length);
      const parsed = JSON.parse(decodeURIComponent(rawValue));
      if (!parsed || !parsed.updatedAt || !parsed.preferences) return null;
      if (now() - parsed.updatedAt > CONSENT_DURATION_MS) return null;
      return parsed;
    } catch (error) {
      return null;
    }
  }

  function readConsent() {
    try {
      const cookieConsent = readConsentCookie();
      if (cookieConsent?.preferences) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cookieConsent));
        return cookieConsent.preferences;
      }

      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return null;
      const parsed = JSON.parse(raw);

      if (!parsed || !parsed.updatedAt || !parsed.preferences) return null;
      if (now() - parsed.updatedAt > CONSENT_DURATION_MS) return null;

      return parsed.preferences;
    } catch (error) {
      return null;
    }
  }

  function saveConsent(preferences) {
    const payload = {
      updatedAt: now(),
      preferences: {
        necessary: true,
        analytics: !!preferences.analytics,
        marketing: !!preferences.marketing
      }
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(payload));
    writeConsentCookie(payload);
    window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: payload.preferences }));
  }

  function updateConsentMode(preferences) {
    const consentPayload = {
      ad_storage: preferences.marketing ? 'granted' : 'denied',
      analytics_storage: preferences.analytics ? 'granted' : 'denied',
      ad_user_data: preferences.marketing ? 'granted' : 'denied',
      ad_personalization: preferences.marketing ? 'granted' : 'denied',
      functionality_storage: 'granted',
      security_storage: 'granted'
    };

    if (typeof window.gtag === 'function') {
      window.gtag('consent', 'update', consentPayload);
    }
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: 'cookie_consent_updated', consent: consentPayload });
  }

  // Active les scripts analytiques (stub junior-friendly)
  function enableAnalytics() {
    // Placez ici votre initialisation GA/GTM analytics future si necessaire.
    // Ex: injecter le script GTM uniquement apres consentement analytics.
  }

  // Desactive tout tracking non essentiel
  function disableTracking() {
    // Si vous ajoutez des scripts marketing, bloquez-les ici (ou ne les chargez jamais sans consentement).
  }

  function loadDeferredScripts(preferences) {
    const deferredScripts = document.querySelectorAll('script[type="text/plain"][data-cookie-category]');
    deferredScripts.forEach((script) => {
      const category = script.getAttribute('data-cookie-category');
      const shouldEnable =
        category === 'analytics' ? preferences.analytics :
        category === 'marketing' ? preferences.marketing :
        false;

      if (!shouldEnable || script.dataset.loaded === 'true') return;

      const newScript = document.createElement('script');
      if (script.src) newScript.src = script.src;
      if (script.textContent) newScript.textContent = script.textContent;
      for (const attr of script.attributes) {
        if (['type', 'data-cookie-category', 'data-loaded'].includes(attr.name)) continue;
        newScript.setAttribute(attr.name, attr.value);
      }
      script.dataset.loaded = 'true';
      script.parentNode.insertBefore(newScript, script.nextSibling);
    });
  }

  function applyConsent(preferences) {
    updateConsentMode(preferences);
    if (preferences.analytics) enableAnalytics();
    else disableTracking();
    loadDeferredScripts(preferences);
  }

  function showBanner() {
    if (!banner) return;
    banner.hidden = false;
  }

  function hideBanner() {
    if (!banner) return;
    banner.hidden = true;
  }

  function openModal() {
    if (!modal) return;
    const current = readConsent() || getDefaultConsent();
    if (analyticsToggle) analyticsToggle.checked = !!current.analytics;
    if (marketingToggle) marketingToggle.checked = !!current.marketing;
    modal.hidden = false;
  }

  function closeModal() {
    if (!modal) return;
    modal.hidden = true;
  }

  function acceptAll() {
    const preferences = { necessary: true, analytics: true, marketing: true };
    saveConsent(preferences);
    applyConsent(preferences);
    hideBanner();
    closeModal();
  }

  function rejectAll() {
    const preferences = { necessary: true, analytics: false, marketing: false };
    saveConsent(preferences);
    applyConsent(preferences);
    hideBanner();
    closeModal();
  }

  function savePreferences(customPreferences) {
    const preferences = customPreferences || {
      necessary: true,
      analytics: !!analyticsToggle?.checked,
      marketing: !!marketingToggle?.checked
    };
    saveConsent(preferences);
    applyConsent(preferences);
    hideBanner();
    closeModal();
  }

  function handleAction(action) {
    if (!action) return;
    if (action === 'accept-all') acceptAll();
    if (action === 'reject-all') rejectAll();
    if (action === 'open-settings') openModal();
    if (action === 'save-preferences') savePreferences();
  }

  document.querySelectorAll('[data-cookie-action]').forEach((btn) => {
    btn.addEventListener('click', () => handleAction(btn.getAttribute('data-cookie-action')));
  });

  // Fermeture modal via clic externe
  modal?.addEventListener('click', (event) => {
    if (event.target === modal) closeModal();
  });

  // API simple exposee pour la page /gestion-cookies
  window.cookieConsent = {
    read: readConsent,
    acceptAll,
    rejectAll,
    savePreferences,
    applyConsent,
    openSettings: openModal
  };

  const existingConsent = readConsent();
  if (existingConsent) {
    applyConsent(existingConsent);
    hideBanner();
  } else {
    // Aucun choix: on laisse tout non essentiel bloque et on affiche la banniere
    applyConsent(getDefaultConsent());
    showBanner();
  }
})();
