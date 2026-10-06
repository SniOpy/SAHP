<?php

declare(strict_types=1);

/** @var string $title */
/** @var string|null $intro */
/** @var list<array{route: string, label: string, href: string}> $links */
/** @var string $modifierClass */

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<section class="local-links-section<?= $esc($modifierClass) ?>" aria-labelledby="local-links-title">
  <h2 id="local-links-title"><?= $esc($title) ?></h2>
  <?php if ($intro !== null && trim($intro) !== ''): ?>
    <p class="local-links-intro"><?= $intro ?></p>
  <?php endif; ?>
  <ul class="local-links-grid">
    <?php foreach ($links as $link): ?>
      <li>
        <a href="<?= $esc($link['href']) ?>"><?= $esc($link['label']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
