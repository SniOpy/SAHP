<?php

declare(strict_types=1);

/** @var array<string, string> $formFieldErrors */
/** @var string $fieldMetaTitle */
/** @var string $fieldMetaDescription */

?>
<div class="admin-field-group admin-field-group--seo">
    <p><strong>Référencement (SEO)</strong></p>
    <p class="admin-field-hint">Optionnel. Si vide, le site utilise le titre de l’article et l’extrait sur la page publique.</p>
</div>

<div id="field-meta-title" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'meta_title'), ENT_QUOTES, 'UTF-8') ?>">
    <label for="meta_title">Meta title</label>
    <input
        id="meta_title"
        name="meta_title"
        type="text"
        maxlength="255"
        placeholder="Ex. Curage canalisation à Paris | SAHP"
        value="<?= htmlspecialchars($fieldMetaTitle, ENT_QUOTES, 'UTF-8') ?>"
        <?= isset($formFieldErrors['meta_title']) ? ' aria-invalid="true"' : '' ?>>
    <p class="admin-field-hint">Balise &lt;title&gt; de la page article (255 car. max).</p>
    <?= admin_article_field_error_notice($formFieldErrors, 'meta_title') ?>
</div>

<div id="field-meta-description" class="<?= htmlspecialchars(admin_article_field_group_class($formFieldErrors, 'meta_description'), ENT_QUOTES, 'UTF-8') ?>">
    <label for="meta_description">Meta description</label>
    <textarea
        id="meta_description"
        name="meta_description"
        rows="3"
        maxlength="320"
        class="admin-textarea"
        placeholder="Résumé pour Google (150–160 caractères recommandés)"
        <?= isset($formFieldErrors['meta_description']) ? ' aria-invalid="true"' : '' ?>><?= htmlspecialchars($fieldMetaDescription, ENT_QUOTES, 'UTF-8') ?></textarea>
    <p class="admin-field-hint">Balise meta description (320 car. max).</p>
    <?= admin_article_field_error_notice($formFieldErrors, 'meta_description') ?>
</div>
