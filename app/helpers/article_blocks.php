<?php

declare(strict_types=1);

/**
 * Encodage/décodage articles par blocs ⇄ HTML canonique (éditeur visuel SAHP).
 *
 * Reconstruction depuis du HTML ancien ou non canonique : best-effort uniquement ;
 * après un passage en mode visuel + enregistrement, la structure sera normalisée.
 */

const ARTICLE_BLOCKS_JSON_VERSION = 1;

/** @var int Limites défensives pour éviter des payloads aberrants */
const ARTICLE_BLOCKS_MAX_BLOCKS = 200;
const ARTICLE_BLOCKS_MAX_ITEMS_PER_LIST = 80;
const ARTICLE_BLOCKS_MAX_TEXT_FIELD_LEN = 100000;
const ARTICLE_BLOCKS_MAX_TABLE_COLUMNS = 8;
const ARTICLE_BLOCKS_MAX_TABLE_ROWS = 20;
const ARTICLE_BLOCKS_MAX_TABLE_CELL_LEN = 500;

function is_article_block_image_src_allowed(string $url): bool
{
    $url = trim($url);
    if ($url === '' || strlen($url) > 2048) {
        return false;
    }

    $prefix = BASE_URL . '/uploads/blog/';

    $fileTail = '[a-zA-Z0-9._-]+\.(jpg|jpeg|png|webp)$';

    if (str_starts_with($url, $prefix)) {
        return preg_match('#^' . preg_quote($prefix, '#') . $fileTail . '#i', $url) === 1;
    }

    if (preg_match('#^https?://#', $url) === 1) {
        return str_contains($url, '/uploads/blog/')
            && preg_match('#/uploads/blog/' . $fileTail . '#i', $url) === 1;
    }

    if (str_starts_with($url, '/uploads/blog/')) {
        return preg_match('#^/uploads/blog/' . $fileTail . '#i', $url) === 1;
    }

    return false;
}

function is_article_block_href_allowed(string $href): bool
{
    $href = trim($href);
    if ($href === '' || strlen($href) > 2048) {
        return false;
    }

    if (preg_match('#^https?://#i', $href) === 1) {
        return true;
    }

    if (str_starts_with($href, '/')) {
        return ! str_starts_with($href, '//');
    }

    if (preg_match('#^mailto:#i', $href) === 1) {
        return true;
    }

    return preg_match('#^tel:#i', $href) === 1;
}

/**
 * @return '100'|'75'|'50'|'40'
 */
function article_block_normalize_image_width_key(string $w): string
{
    return in_array($w, ['100', '75', '50', '40'], true) ? $w : '75';
}

function article_block_media_width_modifier_class(string $widthKey): string
{
    return 'blog-block-media--w' . article_block_normalize_image_width_key($widthKey);
}

function article_block_parse_image_width_from_media_class(string $classAttr): string
{
    $c = strtolower($classAttr);
    if (preg_match('/(^|\s)blog-block-media--w(100|75|50|40)(\s|$)/', $c, $m)) {
        return $m[2];
    }

    return '75';
}

function article_block_normalize_bool_emphasis(mixed $v): bool
{
    return $v === true || $v === 1 || $v === '1' || $v === 'true';
}

/**
 * @return 'left'|'center'|'right'
 */
function article_block_normalize_cta_button_align(mixed $v): string
{
    $s = strtolower(trim((string) $v));
    if ($s === 'left' || $s === 'gauche') {
        return 'left';
    }
    if ($s === 'right' || $s === 'droite') {
        return 'right';
    }

    return 'center';
}

/**
 * Classe suffixée sur `.pp-cta-buttons` pour l’alignement du groupe de boutons.
 */
function article_block_cta_buttons_align_modifier_class(string $align): string
{
    return match (article_block_normalize_cta_button_align($align)) {
        'left' => 'pp-cta-buttons--align-left',
        'right' => 'pp-cta-buttons--align-right',
        default => 'pp-cta-buttons--align-center',
    };
}

function article_block_parse_cta_button_align_from_class(string $classAttr): string
{
    $c = strtolower($classAttr);
    if (str_contains($c, 'pp-cta-buttons--align-right')) {
        return 'right';
    }
    if (str_contains($c, 'pp-cta-buttons--align-left')) {
        return 'left';
    }

    return 'center';
}

function article_block_standalone_button_align_modifier_class(string $align): string
{
    return match (article_block_normalize_cta_button_align($align)) {
        'left' => 'blog-block-button--align-left',
        'right' => 'blog-block-button--align-right',
        default => 'blog-block-button--align-center',
    };
}

/**
 * Lecture des classes du wrapper `.blog-block-button` au décodage HTML.
 *
 * @return 'left'|'center'|'right'
 */
function article_block_parse_standalone_button_align_from_wrapper_class(string $classAttr): string
{
    $c = strtolower($classAttr);
    if (str_contains($c, 'blog-block-button--align-right')) {
        return 'right';
    }
    if (str_contains($c, 'blog-block-button--align-center')) {
        return 'center';
    }

    return 'left';
}

function article_block_format_label_with_optional_bold_html(string $plainLabel, bool $bold): string
{
    $e = htmlspecialchars($plainLabel, ENT_QUOTES, 'UTF-8');

    return $bold ? '<strong>' . $e . '</strong>' : $e;
}

/**
 * @return array{type: string, label: string, url: string, bold: bool}|null
 */
function article_block_try_extract_link_from_anchor_element(DOMElement $a): ?array
{
    $href = trim((string) $a->getAttribute('href'));
    if ($href === '') {
        return null;
    }
    $aClass = strtolower($a->getAttribute('class'));
    $plainText = '';
    $bold = false;
    foreach ($a->childNodes as $cn) {
        if ($cn instanceof DOMText) {
            $plainText .= $cn->nodeValue ?? '';
        } elseif ($cn instanceof DOMElement && in_array(strtolower($cn->tagName), ['strong', 'b'], true)) {
            $bold = true;
            $plainText .= $cn->textContent ?? '';
        } else {
            $plainText .= $cn->textContent ?? '';
        }
    }
    $plainText = trim(preg_replace('/\s+/u', ' ', $plainText) ?? '');
    if ($plainText === '' || strlen($plainText) > 500) {
        return null;
    }
    $isPrimaryButtonLink = str_contains($aClass, 'pp-btn');

    return [
        'type' => $isPrimaryButtonLink ? 'standalone_button' : 'link',
        'label' => $plainText,
        'url' => $href,
        'bold' => $bold,
    ];
}

/**
 * @param non-empty-array<string, mixed> $block
 * @throws InvalidArgumentException
 */
function normalize_one_article_block_from_client(array $block): array
{
    $type = (string) ($block['type'] ?? '');
    if ($type === 'text') {
        $body = (string) ($block['body'] ?? '');
        if (strlen($body) > ARTICLE_BLOCKS_MAX_TEXT_FIELD_LEN) {
            throw new InvalidArgumentException('Bloc texte trop long.');
        }

        return ['type' => 'text', 'body' => str_replace(["\r\n", "\r"], "\n", $body)];
    }
    if ($type === 'heading') {
        $level = (int) ($block['level'] ?? 0);
        if ($level !== 2 && $level !== 3) {
            throw new InvalidArgumentException('Niveau de titre invalide (H2 ou H3 uniquement).');
        }
        $text = trim((string) ($block['text'] ?? ''));
        if ($text === '' || strlen($text) > 500) {
            throw new InvalidArgumentException('Le titre du bloc titre est invalide.');
        }

        return ['type' => 'heading', 'level' => $level, 'text' => $text];
    }
    if ($type === 'image') {
        $url = trim((string) ($block['url'] ?? ''));
        if ($url === '') {
            throw new InvalidArgumentException('Bloc image vide : supprimez-le ou téléversez une image.');
        }
        $alt = trim((string) ($block['alt'] ?? ''));
        if (! is_article_block_image_src_allowed($url)) {
            throw new InvalidArgumentException('URL image non autorisée (uploads blog uniquement).');
        }
        if (strlen($alt) > 500) {
            throw new InvalidArgumentException('Texte alternatif trop long.');
        }

        $widthKey = article_block_normalize_image_width_key(trim((string) ($block['width'] ?? '75')));

        return ['type' => 'image', 'url' => $url, 'alt' => $alt, 'width' => $widthKey];
    }
    if ($type === 'bullet_list') {
        $items = $block['items'] ?? null;
        if (! is_array($items)) {
            throw new InvalidArgumentException('Bloc liste invalide.');
        }
        $cleanItems = [];
        foreach ($items as $item) {
            $itemText = trim((string) $item);
            if ($itemText !== '') {
                if (strlen($itemText) > 2000) {
                    throw new InvalidArgumentException('Un élément de liste est trop long.');
                }
                $cleanItems[] = str_replace(["\r\n", "\r"], "\n", $itemText);
            }
        }
        if ($cleanItems === []) {
            throw new InvalidArgumentException('La liste doit contenir au moins un élément.');
        }
        if (count($cleanItems) > ARTICLE_BLOCKS_MAX_ITEMS_PER_LIST) {
            throw new InvalidArgumentException('Trop d’éléments dans la liste.');
        }

        return ['type' => 'bullet_list', 'items' => $cleanItems];
    }
    if ($type === 'cta') {
        $title = trim((string) ($block['title'] ?? ''));
        $subtitle = trim((string) ($block['subtitle'] ?? ''));
        $subtitle = str_replace(["\r\n", "\r"], "\n", $subtitle);
        $buttonLabel = trim((string) ($block['button_label'] ?? ''));
        $buttonUrl = trim((string) ($block['button_url'] ?? ''));
        if ($title === '' || strlen($title) > 300) {
            throw new InvalidArgumentException('Titre bloc CTA invalide.');
        }
        if (strlen($subtitle) > 600) {
            throw new InvalidArgumentException('Texte d’introduction de l’encadré trop long (600 caractères max).');
        }
        if ($buttonLabel === '' || strlen($buttonLabel) > 120) {
            throw new InvalidArgumentException('Libellé du bouton CTA invalide.');
        }
        if (! is_article_block_href_allowed($buttonUrl)) {
            throw new InvalidArgumentException('Lien du bouton CTA non autorisé.');
        }
        $buttonAlign = article_block_normalize_cta_button_align($block['button_align'] ?? 'center');

        return [
            'type' => 'cta',
            'title' => $title,
            'subtitle' => $subtitle,
            'button_label' => $buttonLabel,
            'button_url' => $buttonUrl,
            'button_align' => $buttonAlign,
        ];
    }
    if ($type === 'link') {
        $label = trim((string) ($block['label'] ?? ''));
        $url = trim((string) ($block['url'] ?? ''));
        if ($label === '' || strlen($label) > 500) {
            throw new InvalidArgumentException('Libellé du lien invalide.');
        }
        if (! is_article_block_href_allowed($url)) {
            throw new InvalidArgumentException('URL du lien non autorisée.');
        }

        return [
            'type' => 'link',
            'label' => $label,
            'url' => $url,
            'bold' => article_block_normalize_bool_emphasis($block['bold'] ?? false),
        ];
    }
    if ($type === 'standalone_button') {
        $label = trim((string) ($block['label'] ?? ''));
        $url = trim((string) ($block['url'] ?? ''));
        if ($label === '' || strlen($label) > 120) {
            throw new InvalidArgumentException('Libellé du bouton invalide.');
        }
        if (! is_article_block_href_allowed($url)) {
            throw new InvalidArgumentException('URL du bouton non autorisée.');
        }
        $buttonAlign = article_block_normalize_cta_button_align($block['button_align'] ?? 'left');

        return [
            'type' => 'standalone_button',
            'label' => $label,
            'url' => $url,
            'bold' => article_block_normalize_bool_emphasis($block['bold'] ?? false),
            'button_align' => $buttonAlign,
        ];
    }
    if ($type === 'quote') {
        $body = (string) ($block['body'] ?? '');
        $body = str_replace(["\r\n", "\r"], "\n", $body);
        $body = trim($body);
        if ($body === '') {
            throw new InvalidArgumentException('Le texte de la citation est obligatoire.');
        }
        if (strlen($body) > ARTICLE_BLOCKS_MAX_TEXT_FIELD_LEN) {
            throw new InvalidArgumentException('Citation trop longue.');
        }

        return [
            'type' => 'quote',
            'body' => $body,
            'bold' => article_block_normalize_bool_emphasis($block['bold'] ?? false),
        ];
    }
    if ($type === 'table') {
        return article_block_normalize_table_block_from_client($block);
    }

    throw new InvalidArgumentException('Type de bloc inconnu.');
}

/**
 * @param array<string, mixed> $block
 * @return array{type: string, title: string, columns: int, rows: int, headers: list<string>, rows_data: list<list<string>>}
 */
function article_block_normalize_table_block_from_client(array $block): array
{
    $title = trim((string) ($block['title'] ?? ''));
    if (strlen($title) > 300) {
        throw new InvalidArgumentException('Titre du tableau trop long (300 caractères max).');
    }

    $columns = max(1, min(ARTICLE_BLOCKS_MAX_TABLE_COLUMNS, (int) ($block['columns'] ?? 3)));
    $rows = max(1, min(ARTICLE_BLOCKS_MAX_TABLE_ROWS, (int) ($block['rows'] ?? 3)));

    $headersRaw = $block['headers'] ?? [];
    $headers = [];
    if (is_array($headersRaw)) {
        for ($columnIndex = 0; $columnIndex < $columns; $columnIndex++) {
            $cell = trim((string) ($headersRaw[$columnIndex] ?? ''));
            if (strlen($cell) > ARTICLE_BLOCKS_MAX_TABLE_CELL_LEN) {
                throw new InvalidArgumentException('En-tête de tableau trop long.');
            }
            $headers[] = $cell;
        }
    } else {
        $headers = array_fill(0, $columns, '');
    }

    $rowsDataRaw = $block['rows_data'] ?? [];
    $rowsData = [];
    if (! is_array($rowsDataRaw)) {
        $rowsDataRaw = [];
    }
    for ($rowIndex = 0; $rowIndex < $rows; $rowIndex++) {
        $rowRaw = $rowsDataRaw[$rowIndex] ?? [];
        $rowClean = [];
        if (! is_array($rowRaw)) {
            $rowRaw = [];
        }
        for ($columnIndex = 0; $columnIndex < $columns; $columnIndex++) {
            $cell = trim((string) ($rowRaw[$columnIndex] ?? ''));
            if (strlen($cell) > ARTICLE_BLOCKS_MAX_TABLE_CELL_LEN) {
                throw new InvalidArgumentException('Cellule de tableau trop longue.');
            }
            $rowClean[] = $cell;
        }
        $rowsData[] = $rowClean;
    }

    return [
        'type' => 'table',
        'title' => $title,
        'columns' => $columns,
        'rows' => $rows,
        'headers' => $headers,
        'rows_data' => $rowsData,
    ];
}

/**
 * @throws InvalidArgumentException
 * @return list<array<string, mixed>>
 */
function parse_article_blocks_from_json_string(string $json): array
{
    $decoded = json_decode($json, true);
    if (! is_array($decoded)) {
        throw new InvalidArgumentException('Structure des blocs invalide (JSON).');
    }

    $version = (int) ($decoded['version'] ?? 0);
    if ($version !== ARTICLE_BLOCKS_JSON_VERSION) {
        throw new InvalidArgumentException('Version des blocs non supportée.');
    }

    $blocks = $decoded['blocks'] ?? null;
    if (! is_array($blocks)) {
        throw new InvalidArgumentException('Liste de blocs manquante.');
    }
    if (count($blocks) > ARTICLE_BLOCKS_MAX_BLOCKS) {
        throw new InvalidArgumentException('Trop de blocs.');
    }
    if (count($blocks) < 1) {
        throw new InvalidArgumentException('Ajoutez au moins un bloc de contenu.');
    }

    $normalized = [];
    foreach ($blocks as $block) {
        if (! is_array($block)) {
            throw new InvalidArgumentException('Bloc invalide.');
        }
        /** @var array<string, mixed> $block */
        $blockType = (string) ($block['type'] ?? '');
        if ($blockType === 'image' && trim((string) ($block['url'] ?? '')) === '') {
            continue;
        }
        $normalized[] = normalize_one_article_block_from_client($block);
    }

    if ($normalized === []) {
        throw new InvalidArgumentException('Ajoutez au moins un bloc de contenu.');
    }

    return $normalized;
}

/**
 * @param list<array<string, mixed>> $blocks
 */
function encode_article_blocks_as_html(array $blocks): string
{
    $chunks = [];

    foreach ($blocks as $block) {
        $type = $block['type'] ?? '';

        if ($type === 'text') {
            $body = (string) ($block['body'] ?? '');
            $body = str_replace(["\r\n", "\r"], "\n", $body);
            $body = trim($body);
            if ($body === '') {
                continue;
            }

            foreach (preg_split("/\n{2,}/", $body, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $paragraphRaw) {
                $paragraphRaw = trim($paragraphRaw);
                if ($paragraphRaw === '') {
                    continue;
                }
                $escaped = htmlspecialchars($paragraphRaw, ENT_QUOTES, 'UTF-8');
                $chunks[] = '<p>' . nl2br($escaped, false) . '</p>';
            }

            continue;
        }

        if ($type === 'heading') {
            $level = (int) ($block['level'] ?? 2);
            $tag = $level === 3 ? 'h3' : 'h2';
            $text = htmlspecialchars((string) ($block['text'] ?? ''), ENT_QUOTES, 'UTF-8');
            $chunks[] = '<' . $tag . '>' . $text . '</' . $tag . '>';

            continue;
        }

        if ($type === 'image') {
            $urlRaw = trim((string) ($block['url'] ?? ''));
            if ($urlRaw === '') {
                continue;
            }
            $url = htmlspecialchars($urlRaw, ENT_QUOTES, 'UTF-8');
            $alt = htmlspecialchars((string) ($block['alt'] ?? ''), ENT_QUOTES, 'UTF-8');
            $wSlug = article_block_media_width_modifier_class((string) ($block['width'] ?? '75'));
            $chunks[] = '<div class="blog-block-media ' . $wSlug . '"><img src="' . $url . '" alt="' . $alt . '" loading="lazy" decoding="async"></div>';

            continue;
        }

        if ($type === 'bullet_list') {
            $items = $block['items'] ?? [];
            if (! is_array($items) || $items === []) {
                continue;
            }
            $chunks[] = '<ul>';
            foreach ($items as $item) {
                $chunks[] = '<li>' . htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8') . '</li>';
            }
            $chunks[] = '</ul>';

            continue;
        }

        if ($type === 'cta') {
            $title = htmlspecialchars((string) ($block['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $label = htmlspecialchars((string) ($block['button_label'] ?? ''), ENT_QUOTES, 'UTF-8');
            $href = htmlspecialchars((string) ($block['button_url'] ?? ''), ENT_QUOTES, 'UTF-8');
            $subtitleRaw = trim(str_replace(["\r\n", "\r"], "\n", (string) ($block['subtitle'] ?? '')));
            $subtitleBlock = '';
            if ($subtitleRaw !== '') {
                $subtitleEscaped = htmlspecialchars($subtitleRaw, ENT_QUOTES, 'UTF-8');
                $subtitleBlock = '<p class="pp-cta-subtitle">' . nl2br($subtitleEscaped, false) . '</p>';
            }
            $alignMod = htmlspecialchars(
                article_block_cta_buttons_align_modifier_class((string) ($block['button_align'] ?? 'center')),
                ENT_QUOTES,
                'UTF-8'
            );
            $chunks[] = '<div class="blog-block-cta" role="region" aria-label="Appel à l’action">'
                . '<div class="pp-cta"><h3>' . $title . '</h3>'
                . $subtitleBlock
                . '<div class="pp-cta-buttons ' . $alignMod . '">'
                . '<a class="pp-btn" href="' . $href . '">' . $label . '</a></div>'
                . '</div></div>';

            continue;
        }

        if ($type === 'link') {
            $hrefRaw = trim((string) ($block['url'] ?? ''));
            $inner = article_block_format_label_with_optional_bold_html(
                (string) ($block['label'] ?? ''),
                ! empty($block['bold'])
            );
            $href = htmlspecialchars($hrefRaw, ENT_QUOTES, 'UTF-8');
            $chunks[] = '<div class="blog-block-link"><p><a class="pp-inline-link" href="' . $href . '">' . $inner . '</a></p></div>';

            continue;
        }

        if ($type === 'standalone_button') {
            $hrefRaw = trim((string) ($block['url'] ?? ''));
            $inner = article_block_format_label_with_optional_bold_html(
                (string) ($block['label'] ?? ''),
                ! empty($block['bold'])
            );
            $href = htmlspecialchars($hrefRaw, ENT_QUOTES, 'UTF-8');
            $alignModClean = article_block_standalone_button_align_modifier_class((string) ($block['button_align'] ?? 'left'));
            $chunks[] = '<div class="blog-block-button ' . htmlspecialchars($alignModClean, ENT_QUOTES, 'UTF-8') . '">'
                . '<p><a class="pp-btn" href="' . $href . '">' . $inner . '</a></p></div>';

            continue;
        }

        if ($type === 'quote') {
            $body = trim(str_replace(["\r\n", "\r"], "\n", (string) ($block['body'] ?? '')));
            if ($body === '') {
                continue;
            }
            $escaped = htmlspecialchars($body, ENT_QUOTES, 'UTF-8');
            if (! empty($block['bold'])) {
                $escaped = '<strong>' . $escaped . '</strong>';
            }
            $chunks[] = '<blockquote class="blog-block-quote"><p>' . nl2br($escaped, false) . '</p></blockquote>';

            continue;
        }

        if ($type === 'table') {
            $tableHtml = article_block_encode_table_block_as_html($block);
            if ($tableHtml !== '') {
                $chunks[] = $tableHtml;
            }

            continue;
        }
    }

    return trim(implode("\n", $chunks));
}

/**
 * @param array<string, mixed> $block
 */
function article_block_encode_table_block_as_html(array $block): string
{
    $title = trim((string) ($block['title'] ?? ''));
    $headers = $block['headers'] ?? [];
    $rowsData = $block['rows_data'] ?? [];
    if (! is_array($headers)) {
        $headers = [];
    }
    if (! is_array($rowsData)) {
        $rowsData = [];
    }

    $parts = ['<div class="blog-block-table-wrap">', '<table class="blog-block-table">'];
    if ($title !== '') {
        $parts[] = '<caption>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</caption>';
    }
    $parts[] = '<thead><tr>';
    foreach ($headers as $headerCell) {
        $parts[] = '<th>' . htmlspecialchars((string) $headerCell, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $parts[] = '</tr></thead><tbody>';
    foreach ($rowsData as $row) {
        if (! is_array($row)) {
            continue;
        }
        $parts[] = '<tr>';
        foreach ($row as $cell) {
            $parts[] = '<td>' . htmlspecialchars((string) $cell, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $parts[] = '</tr>';
    }
    $parts[] = '</tbody></table></div>';

    return implode('', $parts);
}

/**
 * Extrait le texte d'un nœud DOM (simple).
 */
function article_block_dom_text_content(DOMNode $node): string
{
    return trim(preg_replace('/\s+/u', ' ', $node->textContent ?? '') ?? '');
}

/**
 * Gabarit si le HTML est vide : un titre H2 et un encadré vides ; libellés d’aide uniquement dans l’interface (placeholders).
 *
 * @return list<array<string, mixed>>
 */
function default_article_editor_blocks_template(): array
{
    return [
        [
            'type' => 'heading',
            'level' => 2,
            'text' => '',
        ],
        [
            'type' => 'cta',
            'title' => '',
            'subtitle' => '',
            'button_label' => '',
            'button_url' => '',
            'button_align' => 'center',
        ],
    ];
}

/**
 * @return list<array<string, mixed>>
 */
function try_decode_article_html_to_blocks(string $html): array
{
    $html = trim($html);
    if ($html === '') {
        return default_article_editor_blocks_template();
    }

    $document = new DOMDocument();
    $wrapped = '<?xml encoding="UTF-8"?><body><div id="article-blocks-root">' . $html . '</div></body>';
    $previous = libxml_use_internal_errors(true);
    $loaded = @$document->loadHTML($wrapped, LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (! $loaded) {
        return [['type' => 'text', 'body' => $html]];
    }

    $rootElement = $document->getElementById('article-blocks-root');
    if ($rootElement === null) {
        return [['type' => 'text', 'body' => $html]];
    }

    $blocks = [];
    foreach ($rootElement->childNodes as $child) {
        if (! ($child instanceof DOMElement)) {
            continue;
        }
        $decoded = decode_one_article_dom_block($child);
        if ($decoded !== null) {
            $blocks[] = $decoded;
        }
    }

    if ($blocks === []) {
        return [['type' => 'text', 'body' => article_block_strip_tags_loose_text($html)]];
    }

    return $blocks;
}

function article_block_strip_tags_loose_text(string $html): string
{
    $text = preg_replace('#<br\s*/?>#i', "\n", $html);
    $text = strip_tags($text ?: '');

    return trim(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

/**
 * @return array<string, mixed>
 */
function decode_article_dom_quote_block(DOMElement $bq): array
{
    /** @var DOMElement|null $p */
    $p = null;
    foreach ($bq->childNodes as $cn) {
        if ($cn instanceof DOMElement && strtolower($cn->tagName) === 'p') {
            $p = $cn;
            break;
        }
    }

    $doc = $bq->ownerDocument;
    if ($p === null) {
        return [
            'type' => 'quote',
            'body' => trim(str_replace(["\r\n", "\r"], "\n", $bq->textContent ?? '')),
            'bold' => false,
        ];
    }

    $firstEl = null;
    foreach ($p->childNodes as $node) {
        if ($node instanceof DOMElement) {
            $firstEl = $node;
            break;
        }
    }

    $bold = $firstEl !== null && strtolower((string) $firstEl->tagName) === 'strong' && $firstEl->nextSibling === null;
    $htmlInner = '';
    foreach ($p->childNodes as $chunk) {
        if ($doc !== null) {
            $htmlInner .= $doc->saveHTML($chunk);
        }
    }

    $body = trim(article_block_strip_tags_loose_text($htmlInner));
    $body = str_replace(["\r\n", "\r"], "\n", $body);

    return ['type' => 'quote', 'body' => $body, 'bold' => $bold];
}

/**
 * @return array<string, mixed>|null
 */
function decode_blog_block_wrapped_anchor_block(DOMElement $div): ?array
{
    $classes = strtolower($div->getAttribute('class'));
    foreach ($div->getElementsByTagName('a') as $candidate) {
        if (! ($candidate instanceof DOMElement)) {
            continue;
        }
        $extracted = article_block_try_extract_link_from_anchor_element($candidate);
        if ($extracted === null) {
            continue;
        }
        $wantStandalone = str_contains($classes, 'blog-block-button');
        if ($wantStandalone) {
            if ($extracted['type'] !== 'standalone_button') {
                continue;
            }
            $standaloneAlign = article_block_parse_standalone_button_align_from_wrapper_class($classes);

            return [
                'type' => 'standalone_button',
                'label' => $extracted['label'],
                'url' => $extracted['url'],
                'bold' => $extracted['bold'],
                'button_align' => $standaloneAlign,
            ];
        }

        if (str_contains($classes, 'blog-block-link') && $extracted['type'] === 'link') {
            return [
                'type' => 'link',
                'label' => $extracted['label'],
                'url' => $extracted['url'],
                'bold' => $extracted['bold'],
            ];
        }
    }

    return null;
}

/**
 * @return array<string, mixed>|null Bloc métier reconnu uniquement pour les éléments top-level canoniques ou legacy courts.
 */
function decode_one_article_dom_block(DOMElement $el): ?array
{
    $tag = strtolower($el->tagName);

    if ($tag === 'blockquote') {
        return decode_article_dom_quote_block($el);
    }

    if ($tag === 'p') {
        $imgs = $el->getElementsByTagName('img');
        if ($imgs->length === 1) {
            $onlyImg = $imgs->item(0);
            if ($onlyImg instanceof DOMElement && $el->childNodes->length <= 5) {
                $src = trim((string) $onlyImg->getAttribute('src'));
                $alt = trim((string) $onlyImg->getAttribute('alt'));
                if ($src !== '') {
                    return ['type' => 'image', 'url' => $src, 'alt' => $alt, 'width' => '75'];
                }
            }
        }

        $elementKids = [];
        $hasVisibleTextBesideStructured = false;
        foreach ($el->childNodes as $n) {
            if ($n instanceof DOMElement) {
                $elementKids[] = $n;
            } elseif ($n instanceof DOMText && trim(str_replace(["\xc2\xa0", "\t"], '', (string) $n->nodeValue)) !== '') {
                $hasVisibleTextBesideStructured = true;
            }
        }

        if (! $hasVisibleTextBesideStructured && count($elementKids) === 1 && strtolower($elementKids[0]->tagName) === 'a') {
            /** @var DOMElement $soloA */
            $soloA = $elementKids[0];
            $extracted = article_block_try_extract_link_from_anchor_element($soloA);
            if ($extracted !== null) {
                if ($extracted['type'] === 'standalone_button') {
                    return [
                        'type' => 'standalone_button',
                        'label' => $extracted['label'],
                        'url' => $extracted['url'],
                        'bold' => $extracted['bold'],
                        'button_align' => 'left',
                    ];
                }

                return [
                    'type' => 'link',
                    'label' => $extracted['label'],
                    'url' => $extracted['url'],
                    'bold' => $extracted['bold'],
                ];
            }
        }

        return ['type' => 'text', 'body' => article_block_extract_paragraph_plain($el)];
    }

    if ($tag === 'h2') {
        $t = article_block_dom_text_content($el);
        if ($t === '') {
            return ['type' => 'text', 'body' => ''];
        }

        return ['type' => 'heading', 'level' => 2, 'text' => $t];
    }

    if ($tag === 'h3') {
        $t = article_block_dom_text_content($el);
        if ($t === '') {
            return ['type' => 'text', 'body' => ''];
        }

        return ['type' => 'heading', 'level' => 3, 'text' => $t];
    }

    if ($tag === 'ul' || $tag === 'ol') {
        return decode_article_dom_list_block($el);
    }

    if ($tag === 'div') {
        $classes = strtolower($el->getAttribute('class'));
        if (str_contains($classes, 'blog-block-media')) {
            $imgs = $el->getElementsByTagName('img');
            $first = $imgs->length > 0 ? $imgs->item(0) : null;
            if ($first instanceof DOMElement) {
                $src = trim((string) $first->getAttribute('src'));
                $alt = trim((string) $first->getAttribute('alt'));

                return $src !== ''
                    ? ['type' => 'image', 'url' => $src, 'alt' => $alt, 'width' => article_block_parse_image_width_from_media_class($classes)]
                    : ['type' => 'text', 'body' => article_block_fallback_plain_from_element($el)];
            }
        }

        if (str_contains($classes, 'blog-block-link') || str_contains($classes, 'blog-block-button')) {
            $decodedWrap = decode_blog_block_wrapped_anchor_block($el);
            if ($decodedWrap !== null) {
                return $decodedWrap;
            }
        }

        if (str_contains($classes, 'blog-block-cta') || str_contains($classes, 'pp-cta')) {
            $decodedCta = decode_article_dom_cta_block($el);
            if ($decodedCta !== null) {
                return $decodedCta;
            }
        }

        if (str_contains($classes, 'blog-block-table-wrap')) {
            $decodedTable = decode_article_dom_table_block($el);
            if ($decodedTable !== null) {
                return $decodedTable;
            }
        }
    }

    if ($tag === 'table' && str_contains(strtolower($el->getAttribute('class')), 'blog-block-table')) {
        $decodedTable = decode_article_dom_table_block($el);
        if ($decodedTable !== null) {
            return $decodedTable;
        }
    }

    return ['type' => 'text', 'body' => article_block_fallback_plain_from_element($el)];
}

/**
 * @return array<string, mixed>|null
 */
function decode_article_dom_table_block(DOMElement $root): ?array
{
    $table = null;
    if (strtolower($root->tagName) === 'table') {
        $table = $root;
    } else {
        $tables = $root->getElementsByTagName('table');
        if ($tables->length > 0) {
            $candidate = $tables->item(0);
            if ($candidate instanceof DOMElement) {
                $table = $candidate;
            }
        }
    }

    if ($table === null) {
        return null;
    }

    $title = '';
    foreach ($table->childNodes as $child) {
        if ($child instanceof DOMElement && strtolower($child->tagName) === 'caption') {
            $title = article_block_dom_text_content($child);
            break;
        }
    }

    $headers = [];
    $theadList = $table->getElementsByTagName('thead');
    if ($theadList->length > 0) {
        $thead = $theadList->item(0);
        if ($thead instanceof DOMElement) {
            $headerRows = $thead->getElementsByTagName('tr');
            if ($headerRows->length > 0) {
                $headerRow = $headerRows->item(0);
                if ($headerRow instanceof DOMElement) {
                    foreach ($headerRow->childNodes as $thNode) {
                        if ($thNode instanceof DOMElement && strtolower($thNode->tagName) === 'th') {
                            $headers[] = article_block_dom_text_content($thNode);
                        }
                    }
                }
            }
        }
    }

    $rowsData = [];
    $tbodyList = $table->getElementsByTagName('tbody');
    $bodyElement = $tbodyList->length > 0 ? $tbodyList->item(0) : $table;
    if ($bodyElement instanceof DOMElement) {
        foreach ($bodyElement->childNodes as $rowNode) {
            if (! $rowNode instanceof DOMElement || strtolower($rowNode->tagName) !== 'tr') {
                continue;
            }
            if ($rowNode->parentNode instanceof DOMElement && strtolower($rowNode->parentNode->tagName) === 'thead') {
                continue;
            }
            $rowCells = [];
            foreach ($rowNode->childNodes as $cellNode) {
                if ($cellNode instanceof DOMElement && in_array(strtolower($cellNode->tagName), ['td', 'th'], true)) {
                    if (strtolower($cellNode->tagName) === 'th' && $headers === []) {
                        $headers[] = article_block_dom_text_content($cellNode);
                    } else {
                        $rowCells[] = article_block_dom_text_content($cellNode);
                    }
                }
            }
            if ($rowCells !== []) {
                $rowsData[] = $rowCells;
            }
        }
    }

    $columns = max(count($headers), 1);
    foreach ($rowsData as $row) {
        $columns = max($columns, count($row));
    }
    $columns = min(ARTICLE_BLOCKS_MAX_TABLE_COLUMNS, $columns);
    $rows = min(ARTICLE_BLOCKS_MAX_TABLE_ROWS, max(count($rowsData), 1));

    while (count($headers) < $columns) {
        $headers[] = '';
    }
    $headers = array_slice($headers, 0, $columns);

    $normalizedRows = [];
    for ($rowIndex = 0; $rowIndex < $rows; $rowIndex++) {
        $sourceRow = $rowsData[$rowIndex] ?? [];
        $rowClean = [];
        for ($columnIndex = 0; $columnIndex < $columns; $columnIndex++) {
            $rowClean[] = trim((string) ($sourceRow[$columnIndex] ?? ''));
        }
        $normalizedRows[] = $rowClean;
    }

    return [
        'type' => 'table',
        'title' => $title,
        'columns' => $columns,
        'rows' => $rows,
        'headers' => $headers,
        'rows_data' => $normalizedRows,
    ];
}

function decode_article_dom_list_block(DOMElement $list): array
{
    $items = [];
    foreach ($list->childNodes as $child) {
        if ($child instanceof DOMElement && strtolower($child->tagName) === 'li') {
            $txt = trim(article_block_inner_html_plain($child));
            if ($txt !== '') {
                $items[] = $txt;
            }
        }
    }
    if ($items === []) {
        return ['type' => 'text', 'body' => article_block_dom_text_content($list)];
    }

    return ['type' => 'bullet_list', 'items' => $items];
}

function decode_article_dom_cta_block(DOMElement $root): ?array
{
    $ppCtaNode = find_article_pp_cta_node($root);

    /** @var DOMElement|null $h3El */
    $h3El = find_first_descendant_dom_element_by_tag($ppCtaNode ?? $root, 'h3');
    if ($h3El === null) {
        return null;
    }
    $title = article_block_dom_text_content($h3El);
    if ($title === '') {
        return null;
    }

    $subtitle = '';
    $ctaWrap = $ppCtaNode ?? $root;
    foreach ($ctaWrap->childNodes as $childOfCta) {
        if ($childOfCta instanceof DOMElement && strtolower($childOfCta->tagName) === 'p') {
            $cls = strtolower($childOfCta->getAttribute('class'));
            if (! str_contains($cls, 'pp-cta-subtitle')) {
                continue;
            }
            $subtitle = article_block_inner_html_plain($childOfCta);
            $subtitle = preg_replace("#\r\n|\r#u", "\n", $subtitle);
            break;
        }
    }

    $linkParent = find_first_pp_cta_buttons($ppCtaNode ?? $root);
    if ($linkParent === null) {
        return null;
    }

    $buttonAlign = article_block_parse_cta_button_align_from_class($linkParent->getAttribute('class'));

    foreach ($linkParent->childNodes as $candidate) {
        if ($candidate instanceof DOMElement && strtolower($candidate->tagName) === 'a') {
            $href = trim((string) $candidate->getAttribute('href'));
            $label = article_block_dom_text_content($candidate);
            if ($href !== '' && $label !== '') {
                return [
                    'type' => 'cta',
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'button_label' => $label,
                    'button_url' => $href,
                    'button_align' => $buttonAlign,
                ];
            }
        }
    }

    return null;
}

function find_article_pp_cta_node(DOMElement $root): ?DOMElement
{
    foreach ($root->getElementsByTagName('div') as $div) {
        if ($div instanceof DOMElement && str_contains(strtolower($div->getAttribute('class')), 'pp-cta')) {
            return $div;
        }
    }

    return null;
}

function find_first_pp_cta_buttons(DOMElement $root): ?DOMElement
{
    foreach ($root->getElementsByTagName('div') as $div) {
        if ($div instanceof DOMElement && str_contains(strtolower($div->getAttribute('class')), 'pp-cta-buttons')) {
            return $div;
        }
    }

    return null;
}

function find_first_descendant_dom_element_by_tag(DOMElement $root, string $tag): ?DOMElement
{
    $tag = strtolower($tag);
    foreach ($root->getElementsByTagName($tag) as $el) {
        if ($el instanceof DOMElement) {
            return $el;
        }
    }

    return null;
}

function article_block_extract_paragraph_plain(DOMElement $p): string
{
    $html = '';
    foreach ($p->childNodes as $cn) {
        if ($cn instanceof DOMText) {
            $html .= htmlspecialchars($cn->nodeValue ?? '', ENT_QUOTES, 'UTF-8');
        } elseif ($cn instanceof DOMElement) {
            $sub = strtolower($cn->tagName);
            if ($sub === 'br') {
                $html .= "\n";
            } else {
                $html .= article_block_inner_html_plain($cn);
            }
        }
    }
    $decoded = trim(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

    return preg_replace('#\s+#u', ' ', $decoded);
}

function article_block_inner_html_plain(DOMElement $element): string
{
    $frag = '';
    foreach ($element->childNodes as $cn) {
        if ($cn instanceof DOMText) {
            $frag .= $cn->nodeValue ?? '';
        } elseif ($cn instanceof DOMElement) {
            $sub = strtolower($cn->tagName);
            if ($sub === 'br') {
                $frag .= "\n";
            } elseif ($sub === 'strong' || $sub === 'em' || $sub === 'b' || $sub === 'i') {
                $frag .= article_block_dom_text_content($cn);
            } else {
                $frag .= article_block_dom_text_content($cn);
            }
        }
    }

    return trim(html_entity_decode($frag, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function article_block_fallback_plain_from_element(DOMElement $el): string
{
    $doc = $el->ownerDocument;
    if ($doc === null) {
        return article_block_dom_text_content($el);
    }

    return article_block_strip_tags_loose_text($doc->saveHTML($el));
}

/**
 * @return array{payload: array{version: int, blocks: list<array<string, mixed>>}}
 */
function build_article_editor_initial_payload_from_html(string $html): array
{
    return [
        'payload' => [
            'version' => ARTICLE_BLOCKS_JSON_VERSION,
            'blocks' => try_decode_article_html_to_blocks($html),
        ],
    ];
}

/**
 * Détermine le HTML brut à passer dans sanitizeArticleContentHtml() selon mode éditeur / droits.
 *
 * @param array<string, mixed> $post
 *
 * @return array{html: string, error: string|null}
 */
function resolve_raw_article_html_from_editor_submission(array $post, bool $userCanEditAdvancedHtml): array
{
    $mode = (string) ($post['content_editor_mode'] ?? 'visual');
    if ($userCanEditAdvancedHtml && $mode === 'advanced') {
        return ['html' => (string) ($post['content'] ?? ''), 'error' => null];
    }

    try {
        $blocks = parse_article_blocks_from_json_string((string) ($post['blocks_json'] ?? ''));
        $html = encode_article_blocks_as_html($blocks);
        if (trim($html) === '') {
            return ['html' => '', 'error' => 'Le contenu est obligatoire.'];
        }

        return ['html' => $html, 'error' => null];
    } catch (Throwable $exception) {
        $message = $exception->getMessage();

        return ['html' => '', 'error' => $message !== '' ? $message : 'Contenu invalide.'];
    }
}
