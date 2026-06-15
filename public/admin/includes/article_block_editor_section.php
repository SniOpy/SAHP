<?php

declare(strict_types=1);

/**
 * @var string $csrfToken
 * @var array<string, mixed> $initialEditorPayload
 * @var string $fieldContentRaw
 */

$jsonInitial = json_encode($initialEditorPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$sanitizedJsonInitial = $jsonInitial !== false ? $jsonInitial : '{"version":1,"blocks":[{"type":"text","body":""}]}';

?>
<div class="article-editor-tabs" role="tablist" aria-label="Mode d’édition du contenu">
    <button type="button" class="article-editor-tab article-editor-tab-active" data-tab="visual" id="tab-visual" role="tab" aria-selected="true" aria-controls="panel-visual">
        CMS
    </button>
    <button type="button" class="article-editor-tab" data-tab="advanced" id="tab-advanced" role="tab" aria-selected="false" aria-controls="panel-advanced">
        Avancé
    </button>
</div>

<input type="hidden" name="content_editor_mode" id="content_editor_mode" value="visual">
<input type="hidden" name="blocks_json" id="blocks_json_field" value="">

<div id="panel-visual" class="article-editor-panel article-editor-panel-active" role="tabpanel" aria-labelledby="tab-visual">
    <div
        id="article-block-editor-root"
        class="article-block-editor article-editor-split-root"
        data-csrf-token="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
        data-upload-url="<?= BASE_URL ?>/admin/articles/upload-content-image.php"
        data-convert-blocks-url="<?= BASE_URL ?>/admin/articles/convert-blocks-to-html.php"
        data-convert-html-url="<?= BASE_URL ?>/admin/articles/convert-html-to-blocks.php"
        data-base-url="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>"
    >
        <div class="article-editor-composer-wrap">
            <aside id="article-block-editor-sidebar" class="article-editor-composer" aria-label="Palette d’éléments pour construire l’article"></aside>
        </div>
        <div id="article-block-editor-main" class="article-editor-article-body" aria-label="Contenu de l’article"></div>
    </div>
</div>

<script type="application/json" id="article-blocks-initial-data"><?= $sanitizedJsonInitial ?></script>

<div id="panel-advanced" class="article-editor-panel article-editor-panel-hidden" role="tabpanel" aria-labelledby="tab-advanced" hidden>
    <?php require __DIR__ . '/article_advanced_html_snippets.php'; ?>
    <div class="admin-field-group article-advanced-editor-field">
        <label for="content_advanced_html">HTML du contenu <span class="admin-required">*</span></label>
        <p class="admin-field-hint">Collez ou saisissez votre HTML dans l’encadré ci-dessous. Utilisez les modèles repliables ou passez depuis l’onglet CMS.</p>
        <div class="article-advanced-editor-shell" role="group" aria-labelledby="content_advanced_html">
            <div class="article-advanced-editor-titlebar">
                <span class="article-advanced-editor-tab article-advanced-editor-tab--active">
                    <span class="article-advanced-editor-tab-icon" aria-hidden="true"></span>
                    <span class="article-advanced-editor-filename">content.html</span>
                </span>
                <span class="article-advanced-editor-lang">HTML</span>
            </div>
            <div class="article-advanced-editor-body">
                <textarea
                    id="content_advanced_html"
                    name="content"
                    rows="18"
                    class="article-advanced-html-input admin-textarea-code"
                    spellcheck="false"
                    autocapitalize="off"
                    autocomplete="off"><?= htmlspecialchars($fieldContentRaw, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
        </div>
    </div>
    <p id="article-editor-sync-message" class="admin-field-hint article-editor-sync-message" role="status" hidden></p>
</div>

<script src="<?= BASE_URL ?>/admin/js/article-block-editor.js?v=<?= SAHP_ASSET_VERSION ?>" defer></script>
