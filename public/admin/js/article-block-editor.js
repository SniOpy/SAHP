/**
 * Éditeur d’article par blocs (vanilla JS) — SAHP admin.
 */
(function () {
  'use strict';

  var root = document.getElementById('article-block-editor-root');
  if (!root) return;

  var ALLOWED_WIDTH = ['100', '75', '50', '40'];

  var initialScript = document.getElementById('article-blocks-initial-data');
  var state = {
    blocks: []
  };

  function makeId() {
    return 'b_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 10);
  }

  function normalizeWidthValue(w) {
    var s = String(w == null ? '75' : w);
    return ALLOWED_WIDTH.indexOf(s) !== -1 ? s : '75';
  }

  function newEmptyBlock(type) {
    switch (type) {
      case 'text':
        return { id: makeId(), type: 'text', body: '' };
      case 'heading':
        return { id: makeId(), type: 'heading', level: 2, text: '' };
      case 'heading3':
        return { id: makeId(), type: 'heading', level: 3, text: '' };
      case 'image':
        return { id: makeId(), type: 'image', url: '', alt: '', width: '75' };
      case 'bullet_list':
        return { id: makeId(), type: 'bullet_list', items: [''] };
      case 'cta':
        return { id: makeId(), type: 'cta', title: '', subtitle: '', button_label: '', button_url: '', button_align: 'center' };
      case 'link':
        return { id: makeId(), type: 'link', label: '', url: '', bold: false };
      case 'standalone_button':
        return { id: makeId(), type: 'standalone_button', label: '', url: '', bold: false, button_align: 'left' };
      case 'quote':
        return { id: makeId(), type: 'quote', body: '', bold: false };
      case 'table':
        return {
          id: makeId(),
          type: 'table',
          title: '',
          columns: 3,
          rows: 3,
          headers: ['Colonne 1', 'Colonne 2', 'Colonne 3'],
          rows_data: [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
          ]
        };
      default:
        return { id: makeId(), type: 'text', body: '' };
    }
  }

  function coerceBlockFromServer(b) {
    if (b.type === 'image') {
      b.width = normalizeWidthValue(b.width);
    }
    if (b.type === 'cta') {
      var align = String(b.button_align || '').toLowerCase();
      b.button_align = align === 'left' || align === 'right' ? align : 'center';
    }
    if (b.type === 'standalone_button') {
      var sbAlign = String(b.button_align || '').toLowerCase();
      b.button_align = sbAlign === 'center' || sbAlign === 'right' ? sbAlign : 'left';
    }
    if (b.type === 'table') {
      normalizeTableBlock(b);
    }
  }

  function toWireBlock(b) {
    switch (b.type) {
      case 'text':
        return { type: 'text', body: typeof b.body === 'string' ? b.body : '' };
      case 'heading':
        return {
          type: 'heading',
          level: b.level === 3 ? 3 : 2,
          text: typeof b.text === 'string' ? b.text : ''
        };
      case 'image':
        return {
          type: 'image',
          url: typeof b.url === 'string' ? b.url : '',
          alt: typeof b.alt === 'string' ? b.alt : '',
          width: normalizeWidthValue(b.width)
        };
      case 'bullet_list':
        return {
          type: 'bullet_list',
          items: Array.isArray(b.items) ? b.items.map(String) : []
        };
      case 'cta':
        return {
          type: 'cta',
          title: typeof b.title === 'string' ? b.title : '',
          subtitle: typeof b.subtitle === 'string' ? b.subtitle : '',
          button_label: typeof b.button_label === 'string' ? b.button_label : '',
          button_url: typeof b.button_url === 'string' ? b.button_url : '',
          button_align: (function () {
            var align = String(b.button_align || '').toLowerCase();
            return align === 'left' || align === 'right' ? align : 'center';
          })()
        };
      case 'link':
        return {
          type: 'link',
          label: typeof b.label === 'string' ? b.label : '',
          url: typeof b.url === 'string' ? b.url : '',
          bold: !!b.bold
        };
      case 'standalone_button':
        return {
          type: 'standalone_button',
          label: typeof b.label === 'string' ? b.label : '',
          url: typeof b.url === 'string' ? b.url : '',
          bold: !!b.bold,
          button_align: (function () {
            var sab = String(b.button_align || '').toLowerCase();
            return sab === 'center' || sab === 'right' ? sab : 'left';
          })()
        };
      case 'quote':
        return {
          type: 'quote',
          body: typeof b.body === 'string' ? b.body : '',
          bold: !!b.bold
        };
      case 'table':
        return {
          type: 'table',
          title: typeof b.title === 'string' ? b.title : '',
          columns: parseInt(b.columns, 10) || 3,
          rows: parseInt(b.rows, 10) || 3,
          headers: Array.isArray(b.headers) ? b.headers.map(String) : [],
          rows_data: Array.isArray(b.rows_data)
            ? b.rows_data.map(function (row) {
                return Array.isArray(row) ? row.map(String) : [];
              })
            : []
        };
      default:
        return { type: 'text', body: '' };
    }
  }

  function normalizeTableBlock(block) {
    if (!block || block.type !== 'table') return block;
    block.columns = Math.max(1, Math.min(8, parseInt(block.columns, 10) || 3));
    block.rows = Math.max(1, Math.min(20, parseInt(block.rows, 10) || 3));
    if (!Array.isArray(block.headers)) block.headers = [];
    if (!Array.isArray(block.rows_data)) block.rows_data = [];
    while (block.headers.length < block.columns) {
      block.headers.push('');
    }
    block.headers = block.headers.slice(0, block.columns);
    while (block.rows_data.length < block.rows) {
      block.rows_data.push([]);
    }
    block.rows_data = block.rows_data.slice(0, block.rows);
    block.rows_data.forEach(function (row, rowIndex) {
      if (!Array.isArray(row)) {
        block.rows_data[rowIndex] = [];
        row = block.rows_data[rowIndex];
      }
      while (row.length < block.columns) {
        row.push('');
      }
      block.rows_data[rowIndex] = row.slice(0, block.columns);
    });
    return block;
  }

  function syncBlocksJsonField() {
    var field = document.getElementById('blocks_json_field');
    if (!field) return;
    var payload = { version: 1, blocks: state.blocks.map(toWireBlock) };
    field.value = JSON.stringify(payload);
  }

  function readInitialPayload() {
    if (!initialScript || !initialScript.textContent) {
      state.blocks = [newEmptyBlock('text')];
      return;
    }
    try {
      var data = JSON.parse(initialScript.textContent);
      var rawBlocks = Array.isArray(data.blocks) ? data.blocks : [];
      state.blocks = rawBlocks.map(function (b) {
        var block = Object.assign({ id: makeId() }, b);
        if (!block.type) block.type = 'text';
        if (block.type === 'bullet_list' && !Array.isArray(block.items)) {
          block.items = [''];
        }
        if (block.type === 'table') {
          normalizeTableBlock(block);
        }
        coerceBlockFromServer(block);
        return block;
      });
      if (state.blocks.length === 0) {
        state.blocks.push(newEmptyBlock('text'));
      }
    } catch (e) {
      state.blocks = [newEmptyBlock('text')];
    }
  }

  function toolbarTypeArg(t) {
    if (t === 'heading3') return 'heading3';
    if (t === 'heading') return 'heading';
    return t;
  }

  function moveBlock(fromIndex, toIndex) {
    if (fromIndex === toIndex) return;
    if (fromIndex < 0 || toIndex < 0) return;
    if (fromIndex >= state.blocks.length || toIndex >= state.blocks.length) return;
    var moved = state.blocks.splice(fromIndex, 1)[0];
    state.blocks.splice(toIndex, 0, moved);
    render();
  }

  function removeBlock(index) {
    if (!window.confirm('Supprimer ce bloc ?')) return;
    state.blocks.splice(index, 1);
    if (state.blocks.length === 0) {
      state.blocks.push(newEmptyBlock('text'));
    }
    render();
  }

  function uploadImage(file, onSuccess, onError) {
    var fd = new FormData();
    fd.append('csrf_token', root.dataset.csrfToken);
    fd.append('content_image_file', file);
    fd.append('response_format', 'json');

    fetch(root.dataset.uploadUrl, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data && data.ok && data.url) {
          onSuccess(data.url);
        } else {
          onError((data && data.message) || 'Échec du téléversement.');
        }
      })
      .catch(function () {
        onError('Erreur réseau lors du téléversement.');
      });
  }

  function setBulletItem(blockIndex, itemIndex, value) {
    var b = state.blocks[blockIndex];
    if (!b || b.type !== 'bullet_list') return;
    if (!Array.isArray(b.items)) b.items = [];
    b.items[itemIndex] = value;
  }

  function addBulletRow(blockIndex) {
    var b = state.blocks[blockIndex];
    if (!b || b.type !== 'bullet_list') return;
    b.items.push('');
    render();
  }

  function removeBulletRow(blockIndex, itemIndex) {
    var b = state.blocks[blockIndex];
    if (!b || b.type !== 'bullet_list') return;
    b.items.splice(itemIndex, 1);
    if (b.items.length === 0) b.items = [''];
    render();
  }

  function appendBoldCheckbox(container, block, index) {
    var row = document.createElement('label');
    row.className = 'article-block-checkbox-label article-block-field-row';
    var cb = document.createElement('input');
    cb.type = 'checkbox';
    cb.setAttribute('data-bind', 'bold');
    cb.setAttribute('data-index', String(index));
    cb.checked = !!block.bold;
    row.appendChild(cb);
    row.appendChild(document.createTextNode(' Mettre en gras'));
    container.appendChild(row);
  }

  function renderBlockToolbar() {
    var wrap = document.createElement('div');
    wrap.className = 'article-block-toolbar';
    wrap.innerHTML =
      '<span class="article-block-toolbar-label">Éléments d’article</span>' +
      '<p class="article-block-toolbar-note">Glissez les blocs pour les réordonner. Les encadrés peuvent être placés où vous voulez dans l’article.</p>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="text">Texte</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="heading">Titre H2</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="heading3">Titre H3</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="image">Image</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="bullet_list">Liste</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="link">Lien</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="standalone_button">Bouton</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="cta">Encadré d’action (titre · texte · bouton)</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="quote">Citation</button>' +
      '<button type="button" class="admin-panel-secondary-btn article-block-add" data-add="table">Tableau</button>';

    wrap.addEventListener('click', function (e) {
      var btn = e.target.closest('.article-block-add');
      if (!btn) return;
      var t = btn.getAttribute('data-add');
      state.blocks.push(newEmptyBlock(toolbarTypeArg(t)));
      render();
    });

    return wrap;
  }

  function renderBlockRow(block, index) {
    var row = document.createElement('div');
    row.className = 'article-block-item';
    row.setAttribute('data-block-index', String(index));

    row.setAttribute('draggable', 'true');

    var handle = document.createElement('span');
    handle.className = 'article-block-drag-handle';
    handle.setAttribute('aria-hidden', 'true');
    handle.title = 'Glisser pour réordonner';
    handle.textContent = '⋮⋮';

    var body = document.createElement('div');
    body.className = 'article-block-item-body';

    var header = document.createElement('div');
    header.className = 'article-block-item-heading';

    var title = document.createElement('span');
    title.className = 'article-block-type-label';

    var delBtn = document.createElement('button');
    delBtn.type = 'button';
    delBtn.className = 'article-block-remove admin-panel-secondary-btn';
    delBtn.textContent = 'Supprimer';

    if (block.type === 'text') {
      title.textContent = 'Texte';
      var ta = document.createElement('textarea');
      ta.className = 'admin-textarea article-block-textarea';
      ta.rows = 5;
      ta.value = block.body || '';
      ta.setAttribute('data-bind', 'body');
      ta.setAttribute('data-index', String(index));
      ta.placeholder = 'Paragraphes. Laissez une ligne vide pour séparer deux paragraphes.';
      body.appendChild(ta);
    } else if (block.type === 'heading') {
      title.textContent = block.level === 3 ? 'Titre H3' : 'Titre H2';
      var hinp = document.createElement('input');
      hinp.type = 'text';
      hinp.className = 'article-block-input';
      hinp.value = block.text || '';
      hinp.setAttribute('data-bind', 'text');
      hinp.setAttribute('data-index', String(index));
      hinp.placeholder =
        block.level === 3
          ? 'Ex. précision technique, sous-partie du sujet… (H3)'
          : 'Ex. titre de la partie principale ou de l’article… (H2)';
      body.appendChild(hinp);
    } else if (block.type === 'image') {
      title.textContent = 'Image';
      var preview = document.createElement('div');
      preview.className = 'article-block-image-preview';
      if (block.url) {
        var previewImg = document.createElement('img');
        previewImg.src = block.url;
        previewImg.alt = block.alt || '';
        preview.appendChild(previewImg);
      }
      var drop = document.createElement('div');
      drop.className = 'article-block-dropzone';
      drop.appendChild(document.createTextNode('Déposez une image ici ou '));
      var fileBtn = document.createElement('button');
      fileBtn.type = 'button';
      fileBtn.className = 'admin-panel-secondary-btn';
      fileBtn.textContent = 'parcourir';
      drop.appendChild(fileBtn);

      var fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.accept = '.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp';
      fileInput.style.display = 'none';

      var altInput = document.createElement('input');
      altInput.type = 'text';
      altInput.className = 'article-block-input';
      altInput.placeholder = 'Texte alternatif (accessibilité)';
      altInput.value = block.alt || '';
      altInput.setAttribute('data-bind', 'alt');
      altInput.setAttribute('data-index', String(index));

      var widthRow = document.createElement('div');
      widthRow.className = 'article-block-field-row';
      widthRow.appendChild(document.createTextNode('Largeur dans l’article : '));
      var wSel = document.createElement('select');
      wSel.className = 'article-block-input';
      wSel.style.maxWidth = '220px';
      wSel.style.marginBottom = '0';
      wSel.setAttribute('data-bind', 'width');
      wSel.setAttribute('data-index', String(index));
      [
        ['100', '100 %'],
        ['75', '75 % (défaut)'],
        ['50', '50 %'],
        ['40', '40 %']
      ].forEach(function (opt) {
        var o = document.createElement('option');
        o.value = opt[0];
        o.textContent = opt[1];
        wSel.appendChild(o);
      });
      wSel.value = normalizeWidthValue(block.width);
      widthRow.appendChild(wSel);
      body.appendChild(widthRow);

      if (block.url) {
        var urlHint = document.createElement('p');
        urlHint.className = 'admin-field-hint';
        urlHint.textContent = 'Image : ' + block.url;
        body.appendChild(urlHint);
      }

      body.appendChild(preview);
      body.appendChild(drop);
      body.appendChild(fileInput);
      body.appendChild(altInput);

      drop.addEventListener('dragover', function (e) {
        e.preventDefault();
        drop.classList.add('article-block-dropzone-active');
      });
      drop.addEventListener('dragleave', function () {
        drop.classList.remove('article-block-dropzone-active');
      });
      drop.addEventListener('drop', function (e) {
        e.preventDefault();
        drop.classList.remove('article-block-dropzone-active');
        var fileDropped = e.dataTransfer.files && e.dataTransfer.files[0];
        if (fileDropped) {
          uploadImage(
            fileDropped,
            function (url) {
              state.blocks[index].url = url;
              render();
            },
            function (msg) {
              window.alert(msg);
            }
          );
        }
      });

      fileBtn.addEventListener('click', function () {
        fileInput.click();
      });
      fileInput.addEventListener('change', function () {
        var fu = fileInput.files && fileInput.files[0];
        if (fu) {
          uploadImage(
            fu,
            function (url) {
              state.blocks[index].url = url;
              render();
            },
            function (msg) {
              window.alert(msg);
            }
          );
        }
        fileInput.value = '';
      });
    } else if (block.type === 'bullet_list') {
      title.textContent = 'Liste à puces';
      var listWrap = document.createElement('div');
      listWrap.className = 'article-block-list-items';
      (block.items || ['']).forEach(function (item, j) {
        var rowIn = document.createElement('div');
        rowIn.className = 'article-block-list-row';
        var bulletInput = document.createElement('input');
        bulletInput.type = 'text';
        bulletInput.className = 'article-block-input';
        bulletInput.value = item;
        bulletInput.setAttribute('data-list-index', String(j));
        bulletInput.placeholder = 'Élément ' + (j + 1);
        rowIn.appendChild(bulletInput);
        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'article-block-remove-line admin-panel-secondary-btn';
        rm.textContent = '×';
        rm.setAttribute('data-list-remove', String(j));
        rowIn.appendChild(rm);
        listWrap.appendChild(rowIn);
      });
      var addLineBtn = document.createElement('button');
      addLineBtn.type = 'button';
      addLineBtn.className = 'admin-panel-secondary-btn';
      addLineBtn.textContent = 'Ajouter une puce';
      addLineBtn.setAttribute('data-list-add', String(index));
      body.appendChild(listWrap);
      body.appendChild(addLineBtn);
    } else if (block.type === 'cta') {
      title.textContent = 'Encadré d’action';
      var c1 = document.createElement('input');
      c1.type = 'text';
      c1.className = 'article-block-input';
      c1.placeholder = 'Titre dans l’encadré…';
      c1.value = block.title || '';
      c1.setAttribute('data-bind', 'title');
      c1.setAttribute('data-index', String(index));
      var cSub = document.createElement('textarea');
      cSub.className = 'admin-textarea article-block-textarea';
      cSub.rows = 3;
      cSub.placeholder = 'Court texte sous le titre (optionnel — zone, disponibilités, précision…)';
      cSub.value = block.subtitle || '';
      cSub.setAttribute('data-bind', 'subtitle');
      cSub.setAttribute('data-index', String(index));
      var c2 = document.createElement('input');
      c2.type = 'text';
      c2.className = 'article-block-input';
      c2.placeholder = 'Libellé du bouton… (ex. Nous contacter)';
      c2.value = block.button_label || '';
      c2.setAttribute('data-bind', 'button_label');
      c2.setAttribute('data-index', String(index));
      var c3 = document.createElement('input');
      c3.type = 'url';
      c3.className = 'article-block-input';
      c3.placeholder = 'URL du lien (ex. /contact ou https://…)';
      c3.value = block.button_url || '';
      c3.setAttribute('data-bind', 'button_url');
      c3.setAttribute('data-index', String(index));
      var alignRow = document.createElement('div');
      alignRow.className = 'article-block-field-row';
      alignRow.appendChild(document.createTextNode('Position du bouton : '));
      var alignSel = document.createElement('select');
      alignSel.className = 'article-block-input';
      alignSel.style.maxWidth = '260px';
      alignSel.setAttribute('data-bind', 'button_align');
      alignSel.setAttribute('data-index', String(index));
      [
        ['center', 'Centré'],
        ['left', 'À gauche'],
        ['right', 'À droite']
      ].forEach(function (pair) {
        var o = document.createElement('option');
        o.value = pair[0];
        o.textContent = pair[1];
        alignSel.appendChild(o);
      });
      var alignCur = String(block.button_align || 'center').toLowerCase();
      alignSel.value = alignCur === 'left' || alignCur === 'right' ? alignCur : 'center';
      alignRow.appendChild(alignSel);
      body.appendChild(c1);
      body.appendChild(cSub);
      body.appendChild(c2);
      body.appendChild(c3);
      body.appendChild(alignRow);
    } else if (block.type === 'link') {
      title.textContent = 'Lien';
      var ln1 = document.createElement('input');
      ln1.type = 'text';
      ln1.className = 'article-block-input';
      ln1.placeholder = 'Texte du lien';
      ln1.value = block.label || '';
      ln1.setAttribute('data-bind', 'label');
      ln1.setAttribute('data-index', String(index));
      var ln2 = document.createElement('input');
      ln2.type = 'url';
      ln2.className = 'article-block-input';
      ln2.placeholder = 'https://... ou /page';
      ln2.value = block.url || '';
      ln2.setAttribute('data-bind', 'url');
      ln2.setAttribute('data-index', String(index));
      body.appendChild(ln1);
      body.appendChild(ln2);
      appendBoldCheckbox(body, block, index);
    } else if (block.type === 'standalone_button') {
      title.textContent = 'Bouton';
      var sb1 = document.createElement('input');
      sb1.type = 'text';
      sb1.className = 'article-block-input';
      sb1.placeholder = 'Texte du bouton';
      sb1.value = block.label || '';
      sb1.setAttribute('data-bind', 'label');
      sb1.setAttribute('data-index', String(index));
      var sb2 = document.createElement('input');
      sb2.type = 'url';
      sb2.className = 'article-block-input';
      sb2.placeholder = 'https://... ou /page';
      sb2.value = block.url || '';
      sb2.setAttribute('data-bind', 'url');
      sb2.setAttribute('data-index', String(index));
      var sbAlignRow = document.createElement('div');
      sbAlignRow.className = 'article-block-field-row';
      sbAlignRow.appendChild(document.createTextNode('Position du bouton : '));
      var sbAlignSel = document.createElement('select');
      sbAlignSel.className = 'article-block-input';
      sbAlignSel.style.maxWidth = '260px';
      sbAlignSel.setAttribute('data-bind', 'button_align');
      sbAlignSel.setAttribute('data-index', String(index));
      [
        ['left', 'À gauche'],
        ['center', 'Centré'],
        ['right', 'À droite']
      ].forEach(function (pair) {
        var o = document.createElement('option');
        o.value = pair[0];
        o.textContent = pair[1];
        sbAlignSel.appendChild(o);
      });
      var sbAlignCur = String(block.button_align || 'left').toLowerCase();
      sbAlignSel.value = sbAlignCur === 'center' || sbAlignCur === 'right' ? sbAlignCur : 'left';
      sbAlignRow.appendChild(sbAlignSel);
      body.appendChild(sb1);
      body.appendChild(sb2);
      body.appendChild(sbAlignRow);
      appendBoldCheckbox(body, block, index);
    } else if (block.type === 'quote') {
      title.textContent = 'Citation';
      var qta = document.createElement('textarea');
      qta.className = 'admin-textarea article-block-textarea';
      qta.rows = 4;
      qta.value = block.body || '';
      qta.setAttribute('data-bind', 'body');
      qta.setAttribute('data-index', String(index));
      qta.placeholder = 'Citation (texte seul)';
      body.appendChild(qta);
      appendBoldCheckbox(body, block, index);
    } else if (block.type === 'table') {
      normalizeTableBlock(block);
      title.textContent = 'Tableau';
      var tableTitle = document.createElement('input');
      tableTitle.type = 'text';
      tableTitle.className = 'article-block-input';
      tableTitle.placeholder = 'Titre du tableau (caption)';
      tableTitle.value = block.title || '';
      tableTitle.setAttribute('data-bind', 'title');
      tableTitle.setAttribute('data-index', String(index));
      var dimRow = document.createElement('div');
      dimRow.className = 'article-block-field-row';
      dimRow.appendChild(document.createTextNode('Colonnes : '));
      var colInput = document.createElement('input');
      colInput.type = 'number';
      colInput.min = '1';
      colInput.max = '8';
      colInput.className = 'article-block-input';
      colInput.style.maxWidth = '80px';
      colInput.value = String(block.columns);
      colInput.setAttribute('data-bind', 'table_columns');
      colInput.setAttribute('data-index', String(index));
      dimRow.appendChild(colInput);
      dimRow.appendChild(document.createTextNode(' Lignes : '));
      var rowInput = document.createElement('input');
      rowInput.type = 'number';
      rowInput.min = '1';
      rowInput.max = '20';
      rowInput.className = 'article-block-input';
      rowInput.style.maxWidth = '80px';
      rowInput.value = String(block.rows);
      rowInput.setAttribute('data-bind', 'table_rows');
      rowInput.setAttribute('data-index', String(index));
      dimRow.appendChild(rowInput);
      var grid = document.createElement('div');
      grid.className = 'article-block-table-grid';
      var headerRow = document.createElement('div');
      headerRow.className = 'article-block-table-grid-row article-block-table-grid-row--header';
      block.headers.forEach(function (headerCell, colIndex) {
        var headerInput = document.createElement('input');
        headerInput.type = 'text';
        headerInput.className = 'article-block-input';
        headerInput.placeholder = 'En-tête ' + (colIndex + 1);
        headerInput.value = headerCell || '';
        headerInput.setAttribute('data-bind', 'table_header');
        headerInput.setAttribute('data-index', String(index));
        headerInput.setAttribute('data-col', String(colIndex));
        headerRow.appendChild(headerInput);
      });
      grid.appendChild(headerRow);
      block.rows_data.forEach(function (dataRow, rowIndex) {
        var dataRowEl = document.createElement('div');
        dataRowEl.className = 'article-block-table-grid-row';
        dataRow.forEach(function (cellValue, colIndex) {
          var cellInput = document.createElement('input');
          cellInput.type = 'text';
          cellInput.className = 'article-block-input';
          cellInput.placeholder = 'Ligne ' + (rowIndex + 1);
          cellInput.value = cellValue || '';
          cellInput.setAttribute('data-bind', 'table_cell');
          cellInput.setAttribute('data-index', String(index));
          cellInput.setAttribute('data-row', String(rowIndex));
          cellInput.setAttribute('data-col', String(colIndex));
          dataRowEl.appendChild(cellInput);
        });
        grid.appendChild(dataRowEl);
      });
      body.appendChild(tableTitle);
      body.appendChild(dimRow);
      body.appendChild(grid);
    }

    header.appendChild(title);
    header.appendChild(delBtn);

    row.appendChild(handle);
    row.appendChild(body);
    body.insertBefore(header, body.firstChild);

    row.addEventListener('dragstart', function (e) {
      e.dataTransfer.setData('text/plain', String(index));
      e.dataTransfer.effectAllowed = 'move';
      row.classList.add('article-block-item-dragging');
    });
    row.addEventListener('dragend', function () {
      row.classList.remove('article-block-item-dragging');
    });
    row.addEventListener('dragover', function (e) {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
    });
    row.addEventListener('drop', function (e) {
      e.preventDefault();
      var fromStr = e.dataTransfer.getData('text/plain');
      var fromIx = parseInt(fromStr, 10);
      if (Number.isNaN(fromIx)) return;
      moveBlock(fromIx, index);
    });

    delBtn.addEventListener('click', function () {
      removeBlock(index);
    });

    return row;
  }

  function applyDataBindField(el) {
    var idx = parseInt(el.getAttribute('data-index') || '', 10);
    if (Number.isNaN(idx) || !state.blocks[idx]) return;
    var bind = el.getAttribute('data-bind');
    if (!bind) return;
    if (bind === 'bold' && el.type === 'checkbox') {
      state.blocks[idx].bold = el.checked;
    } else if (bind === 'width') {
      state.blocks[idx].width = normalizeWidthValue(el.value);
    } else if (bind === 'button_align') {
      var bv = String(el.value || '').toLowerCase();
      var balign = state.blocks[idx];
      if (balign.type === 'standalone_button') {
        balign.button_align = bv === 'center' || bv === 'right' ? bv : 'left';
      } else {
        balign.button_align = bv === 'left' || bv === 'right' ? bv : 'center';
      }
    } else if (bind === 'table_columns' || bind === 'table_rows') {
      var tableBlock = state.blocks[idx];
      if (tableBlock.type !== 'table') return;
      normalizeTableBlock(tableBlock);
      if (bind === 'table_columns') {
        tableBlock.columns = Math.max(1, Math.min(8, parseInt(el.value, 10) || 1));
      } else {
        tableBlock.rows = Math.max(1, Math.min(20, parseInt(el.value, 10) || 1));
      }
      normalizeTableBlock(tableBlock);
      render();
      return;
    } else if (bind === 'table_header') {
      var colIndex = parseInt(el.getAttribute('data-col') || '', 10);
      if (!Number.isNaN(colIndex) && state.blocks[idx].type === 'table') {
        normalizeTableBlock(state.blocks[idx]);
        state.blocks[idx].headers[colIndex] = el.value;
      }
    } else if (bind === 'table_cell') {
      var cellRow = parseInt(el.getAttribute('data-row') || '', 10);
      var cellCol = parseInt(el.getAttribute('data-col') || '', 10);
      if (
        !Number.isNaN(cellRow) &&
        !Number.isNaN(cellCol) &&
        state.blocks[idx].type === 'table'
      ) {
        normalizeTableBlock(state.blocks[idx]);
        if (!state.blocks[idx].rows_data[cellRow]) {
          state.blocks[idx].rows_data[cellRow] = [];
        }
        state.blocks[idx].rows_data[cellRow][cellCol] = el.value;
      }
    } else {
      state.blocks[idx][bind] = el.value;
    }
    syncBlocksJsonField();
  }

  function bindDelegationOnce(container) {
    container.addEventListener('input', function (e) {
      var el = e.target;
      if (!el.getAttribute || !el.getAttribute('data-bind')) return;
      var bindName = el.getAttribute('data-bind');
      if (bindName === 'table_columns' || bindName === 'table_rows') return;
      if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
        if (el.type === 'checkbox') return;
        applyDataBindField(el);
      }
    });

    container.addEventListener('change', function (e) {
      var el = e.target;
      if (!el.getAttribute || !el.getAttribute('data-bind')) return;
      if (el.tagName === 'SELECT') {
        applyDataBindField(el);
      }
      if (el.tagName === 'INPUT' && (el.type === 'checkbox' || el.type === 'number')) {
        applyDataBindField(el);
      }
    });

    container.addEventListener(
      'input',
      function (e) {
        var inp = e.target;
        if (inp.tagName !== 'INPUT' || !inp.closest('.article-block-list-items')) {
          return;
        }
        var rowEl = inp.closest('.article-block-item');
        if (!rowEl) return;
        var blockIndex = parseInt(rowEl.getAttribute('data-block-index') || '', 10);
        var listIndex = parseInt(inp.getAttribute('data-list-index') || '', 10);
        if (Number.isNaN(blockIndex) || Number.isNaN(listIndex)) return;
        setBulletItem(blockIndex, listIndex, inp.value);
        syncBlocksJsonField();
      },
      true
    );

    container.addEventListener('click', function (e) {
      var addBtn = e.target.closest('[data-list-add]');
      if (addBtn) {
        var bi = parseInt(addBtn.getAttribute('data-list-add') || '', 10);
        if (!Number.isNaN(bi)) addBulletRow(bi);
        syncBlocksJsonField();
        return;
      }
      var rmBtn = e.target.closest('[data-list-remove]');
      if (rmBtn) {
        var prow = rmBtn.closest('.article-block-item');
        if (!prow) return;
        var bi2 = parseInt(prow.getAttribute('data-block-index') || '', 10);
        var lj = parseInt(rmBtn.getAttribute('data-list-remove') || '', 10);
        if (!Number.isNaN(bi2) && !Number.isNaN(lj)) removeBulletRow(bi2, lj);
        syncBlocksJsonField();
      }
    });
  }

  function render() {
    var sidebarEl = document.getElementById('article-block-editor-sidebar');
    var mainEl = document.getElementById('article-block-editor-main');
    if (!sidebarEl || !mainEl) return;
    sidebarEl.innerHTML = '';
    mainEl.innerHTML = '';
    sidebarEl.appendChild(renderBlockToolbar());
    var list = document.createElement('div');
    list.className = 'article-block-list';
    state.blocks.forEach(function (block, ix) {
      list.appendChild(renderBlockRow(block, ix));
    });
    mainEl.appendChild(list);
    syncBlocksJsonField();
  }

  function getAdvancedTextarea() {
    return document.getElementById('content_advanced_html');
  }

  function showSyncMessage(text, isError) {
    var msg = document.getElementById('article-editor-sync-message');
    if (!msg) return;
    msg.textContent = text || '';
    msg.hidden = text === '';
    msg.classList.toggle('article-editor-sync-message--error', !!isError);
  }

  function postConvert(url, formData) {
    return fetch(url, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    }).then(function (response) {
      return response.json().then(function (data) {
        return { ok: response.ok, data: data };
      });
    });
  }

  function setupSnippetsAccordion() {
    var wrapper = document.getElementById('article-advanced-snippets');
    var toggleBtn = document.getElementById('article-advanced-snippets-toggle');
    var panel = document.getElementById('article-advanced-snippets-panel');
    if (!wrapper || !toggleBtn || !panel) return;

    function setExpanded(expanded) {
      wrapper.classList.toggle('is-collapsed', !expanded);
      panel.hidden = !expanded;
      toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      toggleBtn.textContent = expanded ? 'Masquer les modèles' : 'Afficher les modèles';
    }

    toggleBtn.addEventListener('click', function () {
      setExpanded(wrapper.classList.contains('is-collapsed'));
    });

    setExpanded(false);
  }

  function setupSnippetInserts() {
    var snippetsScript = document.getElementById('article-advanced-snippets-data');
    var snippets = [];
    if (snippetsScript && snippetsScript.textContent) {
      try {
        snippets = JSON.parse(snippetsScript.textContent);
      } catch (e) {
        snippets = [];
      }
    }
    document.querySelectorAll('.article-advanced-snippet-insert').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var ta = getAdvancedTextarea();
        if (!ta) return;
        var snippetIndex = parseInt(btn.getAttribute('data-snippet-index') || '', 10);
        if (Number.isNaN(snippetIndex) || !snippets[snippetIndex]) return;
        var code = snippets[snippetIndex].code || '';
        var start = ta.selectionStart || 0;
        var end = ta.selectionEnd || 0;
        var before = ta.value.substring(0, start);
        var after = ta.value.substring(end);
        ta.value = before + code + after;
        ta.focus();
        ta.selectionStart = ta.selectionEnd = start + code.length;
        ta.dispatchEvent(new Event('input', { bubbles: true }));
      });
    });
  }

  function setupTabs() {
    var modes = document.getElementById('content_editor_mode');
    var tabVisual = document.getElementById('tab-visual');
    var tabAdvanced = document.getElementById('tab-advanced');
    var panelVisual = document.getElementById('panel-visual');
    var panelAdvanced = document.getElementById('panel-advanced');
    var ta = getAdvancedTextarea();
    var advancedHtmlDirty = false;

    function markAdvancedHtmlDirty() {
      if (modes && modes.value === 'advanced') {
        advancedHtmlDirty = true;
      }
    }

    if (ta) {
      ta.addEventListener('input', markAdvancedHtmlDirty);
    }

    function applyVisualPanelState() {
      if (modes) modes.value = 'visual';
      if (tabVisual) {
        tabVisual.classList.add('article-editor-tab-active');
        tabVisual.setAttribute('aria-selected', 'true');
      }
      if (tabAdvanced) {
        tabAdvanced.classList.remove('article-editor-tab-active');
        tabAdvanced.setAttribute('aria-selected', 'false');
      }
      if (panelVisual) {
        panelVisual.hidden = false;
        panelVisual.classList.add('article-editor-panel-active');
        panelVisual.classList.remove('article-editor-panel-hidden');
      }
      if (panelAdvanced) {
        panelAdvanced.hidden = true;
        panelAdvanced.classList.remove('article-editor-panel-active');
        panelAdvanced.classList.add('article-editor-panel-hidden');
      }
      if (ta) ta.disabled = true;
      var bf = document.getElementById('blocks_json_field');
      if (bf) bf.disabled = false;
    }

    function applyAdvancedPanelState() {
      if (modes) modes.value = 'advanced';
      if (tabVisual) {
        tabVisual.classList.remove('article-editor-tab-active');
        tabVisual.setAttribute('aria-selected', 'false');
      }
      if (tabAdvanced) {
        tabAdvanced.classList.add('article-editor-tab-active');
        tabAdvanced.setAttribute('aria-selected', 'true');
      }
      if (panelVisual) {
        panelVisual.hidden = true;
        panelVisual.classList.remove('article-editor-panel-active');
        panelVisual.classList.add('article-editor-panel-hidden');
      }
      if (panelAdvanced) {
        panelAdvanced.hidden = false;
        panelAdvanced.classList.add('article-editor-panel-active');
        panelAdvanced.classList.remove('article-editor-panel-hidden');
      }
      if (ta) {
        ta.disabled = false;
        ta.focus();
      }
      var bfAdv = document.getElementById('blocks_json_field');
      if (bfAdv) bfAdv.disabled = true;
    }

    function switchToVisual() {
      var convertUrl = root.dataset.convertHtmlUrl;
      if (!advancedHtmlDirty || !convertUrl || !ta) {
        applyVisualPanelState();
        showSyncMessage('');
        return;
      }
      if (
        !window.confirm(
          'Convertir le HTML vers le mode CMS ? Le HTML doit utiliser les classes SAHP (modèles fournis).'
        )
      ) {
        return;
      }
      var fd = new FormData();
      fd.append('csrf_token', root.dataset.csrfToken);
      fd.append('content', ta.value);
      showSyncMessage('Conversion HTML → CMS…');
      postConvert(convertUrl, fd)
        .then(function (result) {
          if (!result.ok || !result.data || !result.data.ok) {
            throw new Error(
              (result.data && result.data.message) || 'Conversion impossible.'
            );
          }
          var payload = result.data.payload;
          var rawBlocks = payload && Array.isArray(payload.blocks) ? payload.blocks : [];
          state.blocks = rawBlocks.map(function (b) {
            var block = Object.assign({ id: makeId() }, b);
            if (!block.type) block.type = 'text';
            if (block.type === 'bullet_list' && !Array.isArray(block.items)) {
              block.items = [''];
            }
            if (block.type === 'table') {
              normalizeTableBlock(block);
            }
            coerceBlockFromServer(block);
            return block;
          });
          if (state.blocks.length === 0) {
            state.blocks.push(newEmptyBlock('text'));
          }
          render();
          advancedHtmlDirty = false;
          applyVisualPanelState();
          showSyncMessage('Contenu synchronisé depuis le HTML.');
        })
        .catch(function (err) {
          showSyncMessage(err.message || 'Conversion impossible.', true);
        });
    }

    function switchToAdvanced() {
      applyAdvancedPanelState();

      var convertUrl = root.dataset.convertBlocksUrl;
      if (!convertUrl) {
        return;
      }

      syncBlocksJsonField();
      var fd = new FormData();
      fd.append('csrf_token', root.dataset.csrfToken);
      fd.append('blocks_json', document.getElementById('blocks_json_field').value);
      showSyncMessage('Conversion CMS → HTML…');
      postConvert(convertUrl, fd)
        .then(function (result) {
          if (!result.ok || !result.data || !result.data.ok) {
            throw new Error(
              (result.data && result.data.message) || 'Conversion impossible.'
            );
          }
          if (ta) {
            ta.value = result.data.html || '';
            advancedHtmlDirty = false;
            ta.focus();
          }
          showSyncMessage('Contenu synchronisé depuis le CMS.');
        })
        .catch(function (err) {
          showSyncMessage(
            (err.message || 'Conversion impossible.') +
              ' Vous pouvez éditer le HTML ci-dessous.',
            true
          );
          if (ta) ta.focus();
        });
    }

    if (tabVisual) {
      tabVisual.addEventListener('click', switchToVisual);
    }
    if (tabAdvanced) {
      tabAdvanced.addEventListener('click', switchToAdvanced);
    }

    applyVisualPanelState();
  }

  function setupFormSubmit() {
    var form = root.closest('form');
    if (!form) return;
    form.addEventListener('submit', function () {
      var modes = document.getElementById('content_editor_mode');
      var mode = modes ? modes.value : 'visual';
      if (mode === 'visual') {
        syncBlocksJsonField();
        var taAdv = document.getElementById('content_advanced_html');
        if (taAdv) taAdv.disabled = true;
      } else {
        var bfFld = document.getElementById('blocks_json_field');
        if (bfFld) bfFld.disabled = true;
      }
    });
  }

  readInitialPayload();
  bindDelegationOnce(root);
  render();
  setupTabs();
  setupSnippetsAccordion();
  setupSnippetInserts();
  setupFormSubmit();
})();
