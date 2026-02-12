<?php

declare(strict_types=1);

/**
 * Helpers pour l'optimisation des performances
 */

/**
 * Détermine quels fichiers CSS charger selon la page
 * 
 * @param string $page Le nom de la page actuelle
 * @return array Liste des fichiers CSS à charger
 */
function get_css_files_for_page(string $page): array
{
    // CSS toujours nécessaires
    $css = [
        'style.css',
        'homepage/header.css',
        'homepage/footer.css',
    ];

    // CSS spécifiques selon la page
    switch ($page) {
        case 'accueil':
        case '':
            $css = array_merge($css, [
                'homepage/hero.css',
                'homepage/about.css',
                'homepage/services.css',
                'homepage/pricing.css',
                'homepage/reviews.css',
                'homepage/last-articles.css',
                'homepage/blog.css',
            ]);
            break;

        case 'contact':
            $css[] = 'pages/contact.css';
            break;

        case 'devis':
            $css[] = 'pages/devis.css';
            break;

        case 'about':
        case 'a-propos':
            $css[] = 'pages/about.css';
            break;

        case 'curage':
            $css[] = 'pages/curage.css';
            break;

        case 'pompage':
            $css[] = 'pages/pompage.css';
            break;

        case 'inspection':
            $css[] = 'pages/inspection.css';
            break;

        case 'debouchage':
            $css[] = 'pages/debouchage.css';
            break;

        case 'urgence':
            $css[] = 'pages/urgence.css';
            break;

        case 'maintenance-pro':
            $css[] = 'pages/maintenance-pro.css';
            break;

        case 'paroles-de-pro':
            $css[] = 'pages/paroles-de-pro.css';
            break;

        case 'tarifs':
            $css[] = 'homepage/pricing.css';
            break;

        case 'blog_show':
        case 'show':
            $css[] = 'pages/blog-articles.css';
            break;

        case 'mentions':
        case 'cgps':
        case 'pc':
        case 'plansite':
            $css[] = 'pages/' . $page . '.css';
            break;

        case '404':
            $css[] = 'pages/404.css';
            break;
    }

    return $css;
}

/**
 * Génère le CSS critique inline pour le above-the-fold
 * 
 * @return string CSS critique
 */
function get_critical_css(): string
{
    return '
<style id="critical-css">
/* Critical CSS - Above the fold */
*{margin:0;padding:0;box-sizing:border-box}
html,body{overflow-x:hidden}
body{background:linear-gradient(90deg,#4080b7 0%,#5ed0c2 100%);font-family:Roboto,sans-serif;color:#fff;min-height:100vh;font-size:1rem;line-height:1.6}
h1,h2,h3{font-family:Montserrat,sans-serif;font-weight:700}
.navbar{max-width:1350px;margin:20px auto;padding:0 16px;display:flex;align-items:center;justify-content:space-between;position:relative;background:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.25);border-radius:20px;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);box-shadow:0 10px 40px rgba(0,0,0,.15)}
.logo img{width:250px;display:block}
.hero{max-width:1200px;margin:80px auto;padding:0 20px;display:grid;grid-template-columns:1.2fr .8fr;align-items:center;gap:40px}
.hero-content h1{font-size:42px;line-height:1.2;margin-bottom:20px}
</style>';
}

/**
 * Ajoute les attributs d'optimisation à une image
 * 
 * @param string $src Source de l'image
 * @param string $alt Texte alternatif
 * @param bool $lazy Lazy loading (défaut: true)
 * @param string|null $width Largeur
 * @param string|null $height Hauteur
 * @return string Balise img optimisée
 */
function optimized_img(string $src, string $alt, bool $lazy = true, ?string $width = null, ?string $height = null): string
{
    $attrs = [
        'src' => htmlspecialchars($src, ENT_QUOTES, 'UTF-8'),
        'alt' => htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'),
    ];

    if ($lazy) {
        $attrs['loading'] = 'lazy';
        $attrs['decoding'] = 'async';
    } else {
        // Image above-the-fold : fetchpriority high
        $attrs['fetchpriority'] = 'high';
    }

    if ($width) $attrs['width'] = $width;
    if ($height) $attrs['height'] = $height;

    $html = '<img';
    foreach ($attrs as $key => $value) {
        $html .= ' ' . $key . '="' . $value . '"';
    }
    $html .= '>';

    return $html;
}
