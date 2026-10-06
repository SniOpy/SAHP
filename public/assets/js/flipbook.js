import * as pdfjsLib from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.min.mjs';

pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.worker.min.mjs';

const MIN_PAGE_WIDTH = 220;

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

async function initFlipbook(section) {
  const single = section.dataset.single === '1';
  const stage = section.querySelector('[data-flipbook-stage]');
  const book = section.querySelector('[data-flipbook-book]');
  const loader = section.querySelector('[data-flipbook-loader]');
  const progress = section.querySelector('[data-flipbook-progress]');
  const fallback = section.querySelector('[data-flipbook-fallback]');
  const toolbar = section.querySelector('[data-flipbook-toolbar]');
  const prevBtn = section.querySelector('[data-flipbook-prev]');
  const nextBtn = section.querySelector('[data-flipbook-next]');
  const counter = section.querySelector('[data-flipbook-counter]');
  const fullscreenBtn = section.querySelector('[data-flipbook-fullscreen]');

  const showFallback = (error) => {
    console.error('Flipbook:', error);
    loader.hidden = true;
    book.hidden = true;
    toolbar.hidden = true;
    fallback.hidden = false;
  };

  // Largeur de page pour que le livre tienne dans l'écran.
  // StPageFlip ignore maxWidth s'il est inférieur à minWidth.
  const pageWidth = (ratio) => {
    const reserved = document.fullscreenElement === stage ? 110 : 200;
    const availableHeight = Math.max(160, window.innerHeight - reserved);
    let width = Math.floor(availableHeight * ratio);

    if (single) {
      const styles = getComputedStyle(stage);
      const availableWidth = Math.floor(
        stage.clientWidth - parseFloat(styles.paddingLeft) - parseFloat(styles.paddingRight)
      );
      if (availableWidth > 0) width = Math.min(width, availableWidth);
    }

    return Math.max(single ? 160 : MIN_PAGE_WIDTH, width);
  };

  try {
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
      if (!single && (i === 1 || i === pdf.numPages)) page.dataset.density = 'hard';
      page.appendChild(document.createElement('canvas'));
      book.appendChild(page);
      pages.push(page);
    }

    const width = pageWidth(ratio);
    const minWidth = single ? Math.ceil(width / 2) + 1 : MIN_PAGE_WIDTH;
    const flip = new PageFlip(book, {
      width: Math.round(baseViewport.width),
      height: Math.round(baseViewport.height),
      size: 'stretch',
      minWidth,
      maxWidth: width,
      minHeight: Math.round(minWidth / ratio),
      maxHeight: Math.round(width / ratio),
      showCover: !single,
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
        console.error('Flipbook: page ' + (index + 1), error);
      }
    };

    const renderAround = (index) => {
      for (let i = index - 2; i <= index + 3; i++) renderPage(i);
    };

    const updateControls = () => {
      const index = flip.getCurrentPageIndex();
      const total = flip.getPageCount();
      const isSpread = !single && flip.getOrientation() === 'landscape' && index > 0 && index < total - 1;
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

    const resize = () => {
      const nextWidth = pageWidth(ratio);
      const settings = flip.getSettings();
      settings.maxWidth = nextWidth;
      settings.maxHeight = Math.round(nextWidth / ratio);
      if (single) {
        // Largeur du bloc < 2 x minWidth : StPageFlip reste en portrait (une face).
        settings.minWidth = Math.ceil(nextWidth / 2) + 1;
        settings.minHeight = Math.round(settings.minWidth / ratio);
        book.style.maxWidth = nextWidth + 'px';
        book.style.minWidth = settings.minWidth + 'px';
      } else {
        book.style.maxWidth = nextWidth * 2 + 'px';
      }
      flip.update();
      updateControls();
    };

    resize();
    loader.hidden = true;
    toolbar.hidden = false;
    stage.classList.add('is-ready');

    prevBtn.addEventListener('click', () => flip.flipPrev());
    nextBtn.addEventListener('click', () => flip.flipNext());

    document.addEventListener('keydown', (e) => {
      if (e.target.closest('input, textarea, select')) return;
      if (e.key === 'ArrowLeft') flip.flipPrev();
      if (e.key === 'ArrowRight') flip.flipNext();
    });

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
  } catch (error) {
    showFallback(error);
  }
}

document.querySelectorAll('[data-flipbook]').forEach((section) => {
  initFlipbook(section);
});
