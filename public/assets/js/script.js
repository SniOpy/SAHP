// Fonction d'initialisation qui fonctionne avec defer
(function initMenu() {
  // Si le DOM n'est pas encore prêt, attendre DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMenu);
    return;
  }

  const burger = document.querySelector('.burger');
  const navMenu = document.querySelector('.nav-menu');
  const overlay = document.querySelector('.menu-overlay');
  const body = document.body;

  if (!burger || !navMenu) return;

  const isMobile = () => window.innerWidth <= 1293;

  /* =========================
     HELPERS DROPDOWNS
  ========================= */
  const dropdowns = () => Array.from(document.querySelectorAll('.nav-dropdown'));

  function closeAllDropdowns(except = null) {
    dropdowns().forEach((drop) => {
      if (except && drop === except) return;

      const btn = drop.querySelector('.dropdown-toggle');
      const menu = drop.querySelector('.dropdown-menu');
      const chevron = drop.querySelector('.chevron');

      menu?.classList.remove('active');
      chevron?.classList.remove('active');
      btn?.setAttribute('aria-expanded', 'false');
    });
  }

  // ✅ iOS repaint forcing (le vrai fix du "2 taps")
  function forceRepaint(el) {
    if (!el) return;
    void el.offsetHeight;
    requestAnimationFrame(() => {
      el.style.transform = 'translateZ(0)';
      requestAnimationFrame(() => {
        el.style.transform = '';
      });
    });
  }

  function openMenu() {
    burger.classList.add('active');
    navMenu.classList.add('active');
    overlay?.classList.add('active');
    body.style.overflow = 'hidden';
    closeAllDropdowns();
  }

  function closeMenu() {
    burger.classList.remove('active');
    navMenu.classList.remove('active');
    overlay?.classList.remove('active');
    body.style.overflow = '';
    closeAllDropdowns();
  }

  /* =========================
     BURGER
  ========================= */
  burger.addEventListener('click', (e) => {
    e.preventDefault();
    const open = navMenu.classList.contains('active');
    open ? closeMenu() : openMenu();
  });

  overlay?.addEventListener('click', closeMenu);

  /* =========================
     DROPDOWN MOBILE (iPhone OK)
  ========================= */
  dropdowns().forEach((drop) => {
    const btn = drop.querySelector('.dropdown-toggle');
    const menu = drop.querySelector('.dropdown-menu');
    const chevron = drop.querySelector('.chevron');

    if (!btn || !menu) return;

    btn.addEventListener('pointerdown', (e) => {
      if (!isMobile()) return;

      e.preventDefault();

      const willOpen = !menu.classList.contains('active');

      closeAllDropdowns(drop);

      if (willOpen) {
        menu.classList.add('active');
        chevron?.classList.add('active');
        btn.setAttribute('aria-expanded', 'true');

        // ✅ Force affichage iOS immédiat
        forceRepaint(menu);
        forceRepaint(navMenu);
      } else {
        menu.classList.remove('active');
        chevron?.classList.remove('active');
        btn.setAttribute('aria-expanded', 'false');
      }
    });
  });

  /* =========================
     TAP DANS LE MENU (hors dropdown)
     -> ferme dropdowns seulement
  ========================= */
  navMenu.addEventListener('pointerdown', (e) => {
    if (!isMobile()) return;

    const insideDropdown = e.target.closest('.nav-dropdown');
    if (!insideDropdown) {
      closeAllDropdowns();
    }
  });

  /* =========================
     CLICK SUR LIENS => ferme tout
  ========================= */
  document.querySelectorAll('nav > a, .dropdown-menu a').forEach((link) => {
    link.addEventListener('click', () => {
      if (isMobile()) closeMenu();
    });
  });

  /* =========================
     RESIZE => reset
  ========================= */
  window.addEventListener('resize', () => {
    if (!isMobile()) closeMenu();
  });
})();

/* =========================
   BOUTON RETOUR EN HAUT
========================= */
(function backToTop() {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', backToTop);
    return;
  }

  const btn = document.getElementById('back-to-top');
  if (!btn) return;

  const scrollThreshold = 400;

  function toggleVisibility() {
    if (window.scrollY > scrollThreshold) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  }

  function scrollToTop(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  window.addEventListener('scroll', toggleVisibility, { passive: true });
  toggleVisibility();

  btn.addEventListener('click', scrollToTop);
})();

/* =========================
   BOUTON HT / TTC SUR CARTES TARIFAIRES
========================= */
(function pricingHtTtc() {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', pricingHtTtc);
    return;
  }

  const TVA_RATE = 1.20;

  function formatPrice(value) {
    return Number(value).toFixed(2);
  }

  function updateCardPrices(card, showTtc) {
    const prices = card.querySelectorAll('.service-price[data-price-ht]');
    prices.forEach((el) => {
      const ht = parseFloat(el.getAttribute('data-price-ht'), 10);
      if (Number.isNaN(ht)) return;
      const ttc = ht * TVA_RATE;
      const value = showTtc ? formatPrice(ttc) : formatPrice(ht);
      const suffix = showTtc ? ' € TTC' : ' € HT';
      el.textContent = value + suffix;
    });
  }

  function setButtonState(btn, showTtc) {
    btn.classList.toggle('active', showTtc);
    btn.setAttribute('aria-pressed', showTtc ? 'true' : 'false');
    btn.textContent = showTtc ? 'Afficher en HT' : 'Afficher en TTC';
  }

  const cards = document.querySelectorAll('.pricing-category');
  cards.forEach((card) => {
    const hasPrices = card.querySelector('.service-price[data-price-ht]');
    if (!hasPrices) return;

    const bar = document.createElement('div');
    bar.className = 'pricing-ttc-bar';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pricing-ttc-btn';
    btn.setAttribute('aria-pressed', 'false');
    btn.textContent = 'Afficher en TTC';
    bar.appendChild(btn);
    card.insertBefore(bar, card.firstChild);

    btn.addEventListener('click', () => {
      const showTtc = btn.getAttribute('aria-pressed') !== 'true';
      updateCardPrices(card, showTtc);
      setButtonState(btn, showTtc);
    });
  });
})();
