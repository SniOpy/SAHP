<?php

declare(strict_types=1);

/**
 * Bloc FAQ réutilisable pour les pages SAHP.
 *
 * - Rendu HTML accessible (details/summary)
 * - Données structurées FAQPage (JSON-LD) pour les rich results Google (EEAT)
 *
 * Les réponses ($faq['a']) peuvent contenir du HTML léger (liens internes pour
 * varier les ancres). Le JSON-LD utilise une version sans balises.
 *
 * @param array<int, array{q: string, a: string}> $faqs
 */
function sahp_render_faq(array $faqs, string $heading = 'Questions fréquentes sur nos interventions'): void
{
    $faqs = array_values(array_filter(
        $faqs,
        static fn ($f): bool => isset($f['q'], $f['a']) && trim((string) $f['q']) !== '' && trim((string) $f['a']) !== ''
    ));

    if ($faqs === []) {
        return;
    }

    $esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    ?>
    <section class="faq-block" id="faq" aria-labelledby="faq-title">
      <h2 id="faq-title" class="faq-title"><?= $esc($heading) ?></h2>
      <div class="faq-list">
        <?php foreach ($faqs as $index => $faq): ?>
          <details class="faq-item"<?= $index === 0 ? ' open' : '' ?>>
            <summary class="faq-question">
              <h3 class="faq-question-heading"><?= $esc((string) $faq['q']) ?></h3>
            </summary>
            <div class="faq-answer"><?= $faq['a'] ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    $entities = [];
    foreach ($faqs as $faq) {
        $answerText = trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $faq['a'])));
        $entities[] = [
            '@type' => 'Question',
            'name' => (string) $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $answerText,
            ],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $entities,
    ];

    echo "\n" . '<script type="application/ld+json">'
        . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}
