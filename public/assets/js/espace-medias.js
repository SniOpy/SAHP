/* Espace médias SAHP — recherche, filtres, visionneuse, copie de lien */
(function () {
  var q = document.getElementById('mk-q');
  var chips = document.querySelectorAll('.mk-chip');
  var sections = document.querySelectorAll('.mk-section');
  var none = document.getElementById('mk-no-result');
  var current = 'all';

  function apply() {
    var term = (q && q.value || '').trim().toLowerCase();
    var shown = 0;
    sections.forEach(function (sec) {
      var catOk = current === 'all' || sec.dataset.cat === current;
      var visible = 0;
      sec.querySelectorAll('.mk-card').forEach(function (card) {
        var ok = catOk && (!term || card.dataset.search.indexOf(term) !== -1);
        card.hidden = !ok;
        if (ok) visible++;
      });
      sec.hidden = visible === 0;
      shown += visible;
    });
    if (none) none.hidden = shown !== 0;
  }
  chips.forEach(function (c) {
    c.addEventListener('click', function () {
      current = c.dataset.filter;
      chips.forEach(function (o) { o.setAttribute('aria-pressed', o === c ? 'true' : 'false'); });
      apply();
    });
  });
  if (q) q.addEventListener('input', apply);

  // Visionneuse
  var lb = document.getElementById('mk-lightbox');
  var lbImg = document.getElementById('mk-lb-img');
  if (lb && lbImg) {
    document.querySelectorAll('.mk-page [data-view]').forEach(function (b) {
      if (b.tagName === 'A') return;
      b.addEventListener('click', function () {
        lbImg.src = b.dataset.view;
        lbImg.alt = b.dataset.title;
        document.getElementById('mk-lb-title').textContent = b.dataset.title;
        document.getElementById('mk-lb-dl').href = b.dataset.dl;
        if (lb.showModal) lb.showModal(); else window.open(b.dataset.view, '_blank');
      });
    });
    lb.addEventListener('click', function (e) { if (e.target === lb || e.target.hasAttribute('data-close')) lb.close(); });
    lb.addEventListener('close', function () { lbImg.removeAttribute('src'); });
  }

  // Copier le lien
  var toast = document.getElementById('mk-toast'), t;
  function notify(msg) {
    if (!toast) return;
    toast.textContent = msg; toast.classList.add('show');
    clearTimeout(t); t = setTimeout(function () { toast.classList.remove('show'); }, 2200);
  }
  document.querySelectorAll('.mk-page [data-copy]').forEach(function (b) {
    b.addEventListener('click', function () {
      var url = b.dataset.copy;
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(function () { notify('Lien copié'); }, function () { prompt('Copiez ce lien :', url); });
      } else { prompt('Copiez ce lien :', url); }
    });
  });
})();
