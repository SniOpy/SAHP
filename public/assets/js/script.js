document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const navMenu = document.querySelector('.nav-menu');
  const menuOverlay = document.querySelector('.menu-overlay');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  const body = document.body;

  const isMobile = () => window.innerWidth <= 900;

  /* =========================
     BURGER MENU
  ========================= */
  burger.addEventListener('click', function () {
    burger.classList.toggle('active');
    navMenu.classList.toggle('active');

    if (menuOverlay) {
      menuOverlay.classList.toggle('active');
    }

    body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
  });

  if (menuOverlay) {
    menuOverlay.addEventListener('click', closeMenu);
  }

  function closeMenu() {
    burger.classList.remove('active');
    navMenu.classList.remove('active');
    if (menuOverlay) menuOverlay.classList.remove('active');
    body.style.overflow = '';
    closeAllDropdowns();
  }

  /* =========================
     DROPDOWN MOBILE — TOGGLE
  ========================= */
 dropdownToggles.forEach(toggle => {
  toggle.addEventListener('click', function(e) {
    if (window.innerWidth > 900) return;

    e.preventDefault();
    e.stopPropagation();

    const dropdown = this.nextElementSibling;
    const chevron = this.querySelector('.chevron');

    const isOpen = dropdown.classList.contains('active');

    dropdown.classList.toggle('active', !isOpen);
    chevron.classList.toggle('active', !isOpen);

    this.setAttribute('aria-expanded', (!isOpen).toString());
  });
});


  /* =========================
     CLIC SUR LIEN → FERMETURE MENU
  ========================= */
  document.querySelectorAll('.dropdown-menu a, nav > a').forEach(link => {
    link.addEventListener('click', function () {
      if (isMobile()) {
        closeMenu();
      }
    });
  });

  /* =========================
     RESIZE
  ========================= */
  window.addEventListener('resize', function () {
    if (!isMobile()) {
      closeMenu();
    }
  });
});
