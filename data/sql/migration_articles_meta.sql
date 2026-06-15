-- Meta title / description pour les articles de blog (SEO)
-- À exécuter une fois dans phpMyAdmin si la table articles existe déjà.

ALTER TABLE articles
    ADD COLUMN meta_title VARCHAR(255) NULL COMMENT 'Balise <title> (optionnel)' AFTER excerpt,
    ADD COLUMN meta_description VARCHAR(320) NULL COMMENT 'Balise meta description (optionnel)' AFTER meta_title;
