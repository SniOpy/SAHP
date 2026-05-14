<?php

declare(strict_types=1);

/**
 * @var string $csrfToken
 * @var array<string, mixed> $initialEditorPayload
 * @var bool $canEditAdvancedHtml
 * @var string $fieldContentRaw
 */

$jsonInitial = json_encode($initialEditorPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$sanitizedJsonInitial = $jsonInitial !== false ? $jsonInitial : '{"version":1,"blocks":[{"type":"text","body":""}]}';

?>
<div class="article-editor-tabs" role="tablist" aria-label="Mode d’édition du contenu">
    <button type="button" class="article-editor-tab article-editor-tab-active" data-tab="visual" id="tab-visual" role="tab" aria-selected="true" aria-controls="panel-visual">
        Composer
    </button>
    <?php if ($canEditAdvancedHtml): ?>
        <button type="button" class="article-editor-tab" data-tab="advanced" id="tab-advanced" role="tab" aria-selected="false" aria-controls="panel-advanced">
            Avancé (HTML)
        </button>
    <?php endif; ?>
</div>

<input type="hidden" name="content_editor_mode" id="content_editor_mode" value="visual">
<input type="hidden" name="blocks_json" id="blocks_json_field" value="">

<div id="panel-visual" class="article-editor-panel article-editor-panel-active" role="tabpanel" aria-labelledby="tab-visual">
    <div
        id="article-block-editor-root"
        class="article-block-editor article-editor-split-root"
        data-csrf-token="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
        data-upload-url="<?= BASE_URL ?>/admin/articles/upload-content-image.php"
        data-base-url="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>"
    >
        <div class="article-editor-composer-wrap">
            <aside id="article-block-editor-sidebar" class="article-editor-composer" aria-label="Palette d’éléments pour construire l’article"></aside>
        </div>
        <div id="article-block-editor-main" class="article-editor-article-body" aria-label="Contenu de l’article"></div>
    </div>
</div>

<script type="application/json" id="article-blocks-initial-data"><?= $sanitizedJsonInitial ?></script>

<?php if ($canEditAdvancedHtml): ?>
    <div id="panel-advanced" class="article-editor-panel article-editor-panel-hidden" role="tabpanel" aria-labelledby="tab-advanced" hidden>
        <div class="admin-field-group">
            <label for="content_advanced_html">HTML du contenu <span class="admin-required">*</span></label>
            <p class="admin-field-hint">Réservé au support. Les clients utilisent l’onglet Composer.</p>
            <textarea id="content_advanced_html" name="content" rows="16" class="admin-textarea admin-textarea-code"><?= htmlspecialchars($fieldContentRaw, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
    </div>
<?php endif; ?>

<script src="<?= BASE_URL ?>/admin/js/article-block-editor.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
