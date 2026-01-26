document.addEventListener('DOMContentLoaded', () => {
  const burger = document.querySelector('.burger');
  const navMenu = document.querySelector('.nav-menu');
  const overlay = document.querySelector('.menu-overlay');
  const body = document.body;

  if (!burger || !navMenu) return;

  const isMobile = () => window.innerWidth <= 900;

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
});

(function () {
  function setLargeScreenClass() {
    const w = window.innerWidth;
    const h = window.innerHeight;

    // ✅ Détection grand écran : uniquement pour écrans 27" et plus
    // Stratégie : utiliser un seuil de largeur élevé pour exclure les écrans 24"
    // - Écran 24" Full HD (1920x1080) : exclu ✅
    // - Écran 24" 2K (2560x1440) : exclu ✅ (largeur < 2700)
    // - Écran 27" 2K (2560x1440) : peut être exclu, mais généralement utilisé avec zoom
    // - Écran 27" 4K (3840x2160) : inclus ✅
    // - Écrans ultra-larges (3440x1440, etc.) : inclus ✅
    const isLarge = w >= 2700;

    document.documentElement.classList.toggle('large-screen', isLarge);
  }

  setLargeScreenClass();
  window.addEventListener('resize', setLargeScreenClass);
})();
