/* Espace médias SAHP — administration : glisser-déposer, confirmation de suppression, copie de lien */
(function () {
  var dz = document.getElementById('mk-dropzone');
  var input = document.getElementById('mk-files');
  var list = document.getElementById('mk-file-list');
  var form = document.getElementById('mk-upload-form');

  if (dz && input && list) {
    var show = function () {
      list.innerHTML = '';
      Array.prototype.forEach.call(input.files, function (f) {
        var li = document.createElement('li');
        li.textContent = '📄 ' + f.name + ' (' + (f.size / 1048576).toFixed(1).replace('.', ',') + ' Mo)';
        list.appendChild(li);
      });
    };
    input.addEventListener('change', show);
    ['dragenter', 'dragover'].forEach(function (e) { dz.addEventListener(e, function (ev) { ev.preventDefault(); dz.classList.add('mk-over'); }); });
    ['dragleave', 'drop'].forEach(function (e) { dz.addEventListener(e, function () { dz.classList.remove('mk-over'); }); });
    dz.addEventListener('drop', function (ev) {
      ev.preventDefault();
      if (ev.dataTransfer.files.length) { input.files = ev.dataTransfer.files; show(); }
    });
  }

  if (form) {
    form.addEventListener('submit', function () {
      var b = document.getElementById('mk-upload-btn');
      if (b) { b.disabled = true; b.textContent = 'Envoi en cours…'; }
    });
  }

  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!window.confirm(f.dataset.confirm)) e.preventDefault();
    });
  });

  var toast = document.getElementById('mk-toast'), t;
  document.querySelectorAll('[data-copy]').forEach(function (b) {
    b.addEventListener('click', function () {
      var url = b.dataset.copy;
      var done = function () {
        if (!toast) return;
        toast.textContent = 'Lien copié'; toast.classList.add('show');
        clearTimeout(t); t = setTimeout(function () { toast.classList.remove('show'); }, 2000);
      };
      if (navigator.clipboard && window.isSecureContext) navigator.clipboard.writeText(url).then(done, function () { prompt('Copiez ce lien :', url); });
      else prompt('Copiez ce lien :', url);
    });
  });
})();
