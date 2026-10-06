<?php

declare(strict_types=1);

/** @var array{route: string, label: string, cta: string, href: string} $local */

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<div class="pp-card pp-local-page-cta">
  <p><strong><?= $esc($local['cta']) ?></strong></p>
  <p>
    SAHP intervient dans le Val-de-Marne pour le pompage, le débouchage, le curage
    et l'entretien de vos installations d'assainissement.
  </p>
  <a class="pp-side-btn" href="<?= $esc($local['href']) ?>">
    <?= $esc(ucfirst($local['label'])) ?>
  </a>
</div>
