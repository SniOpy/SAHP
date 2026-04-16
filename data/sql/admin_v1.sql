-- SAHP - V1 Admin Auth + preparation blog SQL
-- Compatible MySQL 8+

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT NULL,
    content LONGTEXT NOT NULL COMMENT 'Contenu HTML pour futur editeur riche',
    cover_image VARCHAR(255) NULL COMMENT 'Image de couverture (URL ou chemin)',
    category VARCHAR(120) NULL,
    tags JSON NULL COMMENT 'Liste de tags, ex: [\"curage\", \"urgence\"]',
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_articles_is_published (is_published),
    INDEX idx_articles_published_at (published_at),
    INDEX idx_articles_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exemple de seed admin:
-- 1) Generer un hash en local:
--      php -r "echo password_hash('ChangeMe123!', PASSWORD_DEFAULT), PHP_EOL;"
-- 2) Remplacer <PASTE_PASSWORD_HASH> puis executer:
-- INSERT INTO admin_users (username, password_hash)
-- VALUES ('admin', '<PASTE_PASSWORD_HASH>');
