<section class="cookie-banner" id="cookie-banner" aria-label="Banniere de consentement cookies" hidden>
  <div class="cookie-banner__content">
    <div class="cookie-banner__text">
      <strong class="cookie-banner__title">
        Gestion des cookies SAHP
      </strong>
      <p>
        Nous utilisons des cookies pour ameliorer votre experience, mesurer l'audience
        et personnaliser nos services. Vous pouvez modifier votre choix a tout moment.
      </p>
    </div>
    <div class="cookie-banner__actions">
      <button type="button" class="cookie-btn cookie-btn--primary cookie-btn--accept" data-cookie-action="accept-all">
        Accepter
      </button>
      <button type="button" class="cookie-btn cookie-btn--outline" data-cookie-action="reject-all">
        Refuser
      </button>
      <button type="button" class="cookie-btn cookie-btn--ghost" data-cookie-action="open-settings">
        Personnaliser
      </button>
    </div>
  </div>
</section>

<div class="cookie-modal" id="cookie-modal" role="dialog" aria-modal="true" aria-labelledby="cookie-modal-title" hidden>
  <div class="cookie-modal__card">
    <h2 id="cookie-modal-title">Preferences cookies</h2>
    <p class="cookie-modal__intro">
      Vous pouvez choisir les categories de cookies que vous autorisez.
    </p>

    <label class="cookie-switch">
      <span>Cookies necessaires (toujours actifs)</span>
      <input type="checkbox" checked disabled>
    </label>

    <label class="cookie-switch">
      <span>Cookies analytiques</span>
      <input type="checkbox" id="cookie-analytics-toggle">
    </label>

    <label class="cookie-switch">
      <span>Cookies marketing</span>
      <input type="checkbox" id="cookie-marketing-toggle">
    </label>

    <div class="cookie-modal__actions">
      <button type="button" class="cookie-btn cookie-btn--outline" data-cookie-action="reject-all">Refuser tout</button>
      <button type="button" class="cookie-btn cookie-btn--ghost" data-cookie-action="save-preferences">Sauvegarder preferences</button>
      <button type="button" class="cookie-btn cookie-btn--primary" data-cookie-action="accept-all">Activer tout</button>
    </div>
  </div>
</div>