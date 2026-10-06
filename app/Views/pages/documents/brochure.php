<?php $brochureUrl = BASE_URL . '/assets/docs/brochure.pdf'; ?>
<section class="brochure-section" data-pdf="<?= $brochureUrl ?>">
  <div class="brochure-wrapper">
    <h1 class="brochure-title">Brochure de présentation SAHP</h1>

    <div class="brochure-stage" id="brochure-stage">
      <div class="brochure-loader" id="brochure-loader" role="status" aria-live="polite">
        <span class="brochure-spinner" aria-hidden="true"></span>
        <span class="brochure-loader-text">Chargement de la brochure… <span id="brochure-progress">0%</span></span>
      </div>

      <div class="brochure-book" id="brochure-book" aria-label="Brochure de présentation SAHP"></div>

      <div class="brochure-fallback" id="brochure-fallback" hidden>
        <p>Impossible d'afficher la brochure ici.</p>
        <a href="<?= $brochureUrl ?>" target="_blank" rel="noopener">Ouvrir la brochure</a>
      </div>
      <noscript>
        <div class="brochure-fallback">
          <p>Activez JavaScript pour feuilleter la brochure.</p>
          <a href="<?= $brochureUrl ?>" target="_blank" rel="noopener">Ouvrir la brochure</a>
        </div>
      </noscript>

      <div class="brochure-toolbar" id="brochure-toolbar" hidden>
        <button type="button" class="brochure-tool" id="brochure-prev" aria-label="Page précédente">
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <span class="brochure-counter" id="brochure-counter" aria-live="polite">1 / 1</span>
        <button type="button" class="brochure-tool" id="brochure-next" aria-label="Page suivante">
          <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path d="M9 18l6-6-6-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <span class="brochure-toolbar-sep" aria-hidden="true"></span>
        <button type="button" class="brochure-tool" id="brochure-fullscreen" aria-label="Plein écran">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </button>
        <a class="brochure-tool" href="<?= $brochureUrl ?>" download aria-label="Télécharger le PDF">
          <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M12 4v11m0 0l-4.5-4.5M12 15l4.5-4.5M5 20h14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js" defer></script>
<script type="module">
  import * as pdfjsLib from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.min.mjs';

  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.worker.min.mjs';

  const section = document.querySelector('.brochure-section');
  const stage = document.getElementById('brochure-stage');
  const book = document.getElementById('brochure-book');
  const loader = document.getElementById('brochure-loader');
  const progress = document.getElementById('brochure-progress');
  const fallback = document.getElementById('brochure-fallback');
  const toolbar = document.getElementById('brochure-toolbar');
  const prevBtn = document.getElementById('brochure-prev');
  const nextBtn = document.getElementById('brochure-next');
  const counter = document.getElementById('brochure-counter');
  const fullscreenBtn = document.getElementById('brochure-fullscreen');

  const showFallback = (error) => {
    console.error('Brochure:', error);
    loader.hidden = true;
    book.hidden = true;
    toolbar.hidden = true;
    fallback.hidden = false;
  };

  const MIN_PAGE_WIDTH = 220;

  // Les pages sont limitées en largeur pour que le livre tienne en hauteur dans l'écran.
  // StPageFlip ignore maxWidth s'il est inférieur à minWidth.
  const maxPageWidth = (ratio) => {
    const reserved = document.fullscreenElement ? 110 : 200;
    const availableHeight = window.innerHeight - reserved;
    return Math.max(MIN_PAGE_WIDTH, Math.floor(availableHeight * ratio));
  };

  const waitForPageFlip = () => new Promise((resolve, reject) => {
    if (window.St?.PageFlip) return resolve(window.St.PageFlip);
    const started = Date.now();
    const timer = setInterval(() => {
      if (window.St?.PageFlip) {
        clearInterval(timer);
        resolve(window.St.PageFlip);
      } else if (Date.now() - started > 15000) {
        clearInterval(timer);
        reject(new Error('StPageFlip indisponible'));
      }
    }, 50);
  });

  async function init() {
    const task = pdfjsLib.getDocument({ url: section.dataset.pdf, rangeChunkSize: 262144 });
    task.onProgress = ({ loaded, total }) => {
      if (total) progress.textContent = Math.min(100, Math.round((loaded / total) * 100)) + '%';
    };

    const [pdf, PageFlip] = await Promise.all([task.promise, waitForPageFlip()]);
    const firstPage = await pdf.getPage(1);
    const baseViewport = firstPage.getViewport({ scale: 1 });
    const ratio = baseViewport.width / baseViewport.height;

    const pages = [];
    for (let i = 1; i <= pdf.numPages; i++) {
      const page = document.createElement('div');
      page.className = 'brochure-page';
      if (i === 1 || i === pdf.numPages) page.dataset.density = 'hard';
      page.appendChild(document.createElement('canvas'));
      book.appendChild(page);
      pages.push(page);
    }

    const pageWidth = maxPageWidth(ratio);
    const flip = new PageFlip(book, {
      width: Math.round(baseViewport.width),
      height: Math.round(baseViewport.height),
      size: 'stretch',
      minWidth: MIN_PAGE_WIDTH,
      maxWidth: pageWidth,
      minHeight: Math.round(MIN_PAGE_WIDTH / ratio),
      maxHeight: Math.round(pageWidth / ratio),
      showCover: true,
      usePortrait: true,
      mobileScrollSupport: false,
      maxShadowOpacity: 0.5,
      flippingTime: 800,
    });

    const rendered = new Set();
    const renderPage = async (index) => {
      if (index < 0 || index >= pdf.numPages || rendered.has(index)) return;
      rendered.add(index);
      try {
        const page = index === 0 ? firstPage : await pdf.getPage(index + 1);
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        const cssWidth = flip.getSettings().maxWidth;
        const viewport = page.getViewport({ scale: (cssWidth * dpr) / baseViewport.width });
        const canvas = pages[index].querySelector('canvas');
        canvas.width = Math.floor(viewport.width);
        canvas.height = Math.floor(viewport.height);
        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
      } catch (error) {
        rendered.delete(index);
        console.error('Brochure: page ' + (index + 1), error);
      }
    };

    const renderAround = (index) => {
      for (let i = index - 2; i <= index + 3; i++) renderPage(i);
    };

    const updateControls = () => {
      const index = flip.getCurrentPageIndex();
      const total = flip.getPageCount();
      const isSpread = flip.getOrientation() === 'landscape' && index > 0 && index < total - 1;
      counter.textContent = (isSpread ? `${index + 1}-${index + 2}` : `${index + 1}`) + ` / ${total}`;
      prevBtn.disabled = index <= 0;
      nextBtn.disabled = index >= total - (isSpread ? 2 : 1);
    };

    flip.on('flip', (e) => {
      renderAround(e.data);
      updateControls();
    });
    flip.on('changeOrientation', updateControls);

    flip.loadFromHTML(pages);
    await Promise.all([renderPage(0), renderPage(1), renderPage(2)]);
    renderAround(0);

    loader.hidden = true;
    toolbar.hidden = false;
    stage.classList.add('is-ready');
    updateControls();

    prevBtn.addEventListener('click', () => flip.flipPrev());
    nextBtn.addEventListener('click', () => flip.flipNext());

    document.addEventListener('keydown', (e) => {
      if (e.target.closest('input, textarea, select')) return;
      if (e.key === 'ArrowLeft') flip.flipPrev();
      if (e.key === 'ArrowRight') flip.flipNext();
    });

    const resize = () => {
      const width = maxPageWidth(ratio);
      const settings = flip.getSettings();
      settings.maxWidth = width;
      settings.maxHeight = Math.round(width / ratio);
      book.style.maxWidth = width * 2 + 'px';
      flip.update();
      updateControls();
    };

    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(resize, 150);
    });

    if (!document.fullscreenEnabled) {
      fullscreenBtn.hidden = true;
    }
    fullscreenBtn.addEventListener('click', () => {
      if (document.fullscreenElement) document.exitFullscreen();
      else stage.requestFullscreen();
    });
    document.addEventListener('fullscreenchange', () => {
      const active = document.fullscreenElement === stage;
      fullscreenBtn.setAttribute('aria-label', active ? 'Quitter le plein écran' : 'Plein écran');
      setTimeout(resize, 100);
    });
  }

  init().catch(showFallback);
</script>
