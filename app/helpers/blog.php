<?php

function blog_escape(string $str): string {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function blog_load_posts(): array {
  $path = __DIR__ . '/../data/blog.json';
  if (!file_exists($path)) return [];

  $json = file_get_contents($path);
  $posts = json_decode($json, true);
  if (!is_array($posts)) return [];

  // published only
  $posts = array_filter($posts, fn($p) => !empty($p['published']));

  // sort by date desc
  usort($posts, function($a, $b) {
    $da = strtotime($a['published_at'] ?? '1970-01-01');
    $db = strtotime($b['published_at'] ?? '1970-01-01');
    return $db <=> $da;
  });

  return array_values($posts);
}

function blog_find_post_by_slug(string $slug): ?array {
  $posts = blog_load_posts();
  foreach ($posts as $post) {
    if (($post['slug'] ?? '') === $slug) return $post;
  }
  return null;
}
