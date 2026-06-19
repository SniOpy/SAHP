<?php

declare(strict_types=1);

/**
 * Gabarits HTML alignés sur encode_article_blocks_as_html() — onglet Avancé.
 *
 * @return list<array{label: string, hint: string, code: string}>
 */
function get_article_advanced_html_snippets(): array
{
    $imgPath = BASE_URL . '/uploads/blog/votre-image.jpg';
    $linkUrl = BASE_URL . '/contact';

    return [
        [
            'label' => 'Titre H2',
            'hint' => 'Titre principal de section.',
            'code' => "<h2>Votre titre</h2>",
        ],
        [
            'label' => 'Titre H3',
            'hint' => 'Sous-titre.',
            'code' => "<h3>Votre sous-titre</h3>",
        ],
        [
            'label' => 'Paragraphe',
            'hint' => 'Texte courant. Ligne vide = nouveau paragraphe en mode CMS.',
            'code' => "<p>Votre paragraphe ici.</p>",
        ],
        [
            'label' => 'Liste à puces',
            'hint' => 'Liste non ordonnée.',
            'code' => "<ul>\n  <li>Premier point</li>\n  <li>Deuxième point</li>\n</ul>",
        ],
        [
            'label' => 'Image (75 %)',
            'hint' => 'Uploader l’image via le CMS puis copier l’URL /uploads/blog/…',
            'code' => '<div class="blog-block-media blog-block-media--w75"><img src="' . $imgPath . '" alt="Description" loading="lazy" decoding="async"></div>',
        ],
        [
            'label' => 'Lien',
            'hint' => 'Lien inline dans un paragraphe.',
            'code' => '<div class="blog-block-link"><p><a class="pp-inline-link" href="' . $linkUrl . '">Texte du lien</a></p></div>',
        ],
        [
            'label' => 'Bouton',
            'hint' => 'Bouton d’action seul.',
            'code' => '<div class="blog-block-button blog-block-button--align-left"><p><a class="pp-btn" href="' . $linkUrl . '">Libellé du bouton</a></p></div>',
        ],
        [
            'label' => 'Encadré CTA',
            'hint' => 'Encadré titre + texte + bouton.',
            'code' => '<div class="blog-block-cta" role="region" aria-label="Appel à l’action">'
                . '<div class="pp-cta"><h3>Titre de l’encadré</h3>'
                . '<p class="pp-cta-subtitle">Texte d’introduction.</p>'
                . '<div class="pp-cta-buttons pp-cta-buttons--align-center">'
                . '<a class="pp-btn" href="' . $linkUrl . '">Appeler à l’action</a></div>'
                . '</div></div>',
        ],
        [
            'label' => 'Citation',
            'hint' => 'Bloc citation mis en avant.',
            'code' => "<blockquote class=\"blog-block-quote\"><p>Votre citation.</p></blockquote>",
        ],
        [
            'label' => 'Tableau',
            'hint' => 'Titre (caption), en-têtes et lignes de données.',
            'code' => '<div class="blog-block-table-wrap">'
                . '<table class="blog-block-table">'
                . '<caption>Titre du tableau</caption>'
                . '<thead><tr><th>Colonne 1</th><th>Colonne 2</th></tr></thead>'
                . '<tbody><tr><td>Cellule A</td><td>Cellule B</td></tr></tbody>'
                . '</table></div>',
        ],
        [
            'label' => 'FAQ (5 questions)',
            'hint' => 'Section Questions fréquentes en bas d\'article. Le schema FAQPage est généré automatiquement à l\'affichage.',
            'code' => "<h2>Questions fréquentes</h2>\n"
                . "<h3>Première question fréquente ?</h3>\n"
                . "<p>Réponse détaillée entre 50 et 120 mots. Mentionnez un cas concret observé sur le terrain et, si pertinent, un lien interne vers une page service SAHP.</p>\n"
                . "<h3>Deuxième question fréquente ?</h3>\n"
                . "<p>Deuxième réponse avec un angle différent : délai, tarif, zone d'intervention ou conseil préventif.</p>\n"
                . "<h3>Troisième question fréquente ?</h3>\n"
                . "<p>Troisième réponse.</p>\n"
                . "<h3>Quatrième question fréquente ?</h3>\n"
                . "<p>Quatrième réponse.</p>\n"
                . "<h3>Cinquième question fréquente ?</h3>\n"
                . "<p>Cinquième réponse.</p>",
        ],
    ];
}

/**
 * Aperçu court d’un snippet (lecture seule).
 */
function article_advanced_snippet_preview(string $code, int $maxLines = 3): string
{
    $lines = preg_split("/\r\n|\n|\r/", $code) ?: [];
    $previewLines = array_slice($lines, 0, $maxLines);
    $preview = implode("\n", $previewLines);
    if (count($lines) > $maxLines) {
        $preview .= "\n…";
    }

    return $preview;
}

?>
<div class="article-advanced-snippets is-collapsed" id="article-advanced-snippets">
    <div class="article-advanced-snippets-toolbar">
        <p class="admin-field-hint article-advanced-snippets-intro"><strong>Modèles HTML</strong> — mêmes classes CSS que l’onglet CMS.</p>
        <button
            type="button"
            class="admin-panel-secondary-btn article-advanced-snippets-toggle"
            id="article-advanced-snippets-toggle"
            aria-expanded="false"
            aria-controls="article-advanced-snippets-panel">
            Afficher les modèles
        </button>
    </div>
    <div class="article-advanced-snippets-panel" id="article-advanced-snippets-panel" hidden>
        <div class="article-advanced-snippets-list">
            <?php foreach (get_article_advanced_html_snippets() as $snippetIndex => $snippet): ?>
                <div class="article-advanced-snippet-item">
                    <div class="article-advanced-snippet-head">
                        <span class="article-advanced-snippet-label"><?= htmlspecialchars($snippet['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        <button
                            type="button"
                            class="admin-panel-secondary-btn article-advanced-snippet-insert"
                            data-snippet-index="<?= (int) $snippetIndex ?>">
                            Insérer
                        </button>
                    </div>
                    <p class="admin-field-hint"><?= htmlspecialchars($snippet['hint'], ENT_QUOTES, 'UTF-8') ?></p>
                    <pre class="article-advanced-snippet-preview" aria-hidden="true"><?= htmlspecialchars(article_advanced_snippet_preview($snippet['code']), ENT_QUOTES, 'UTF-8') ?></pre>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script type="application/json" id="article-advanced-snippets-data"><?=
        htmlspecialchars(
            json_encode(get_article_advanced_html_snippets(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?: '[]',
            ENT_QUOTES,
            'UTF-8'
        )
    ?></script>
</div>
