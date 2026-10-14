# Espace médias SAHP

Une page publique où partenaires et clients (syndics, agences, collectivités, entreprises) téléchargent les supports SAHP : logos, cartes de visite, QR codes, plaquettes, photos. Les fichiers se gèrent depuis le panneau d'administration du site, avec le même identifiant que pour les articles du blog.

- Aucune base de données pour les fichiers : le classement est dans `data/espace-medias/media.json`, les originaux dans `data/espace-medias/storage/`.
- PHP 8 avec l'extension GD pour les miniatures (Imagick en option pour l'aperçu des PDF).

## Adresses

| Quoi | Production | Local (WAMP) |
|---|---|---|
| Page publique | `https://sahp-idf.fr/espace-medias` | `http://localhost/sahp/public/espace-medias` |
| Téléchargement d'un fichier | `/espace-medias/telecharger?id=…` | `/sahp/public/espace-medias/telecharger?id=…` |
| ZIP d'une catégorie | `/espace-medias/telecharger?zip=logos` | idem avec `/sahp/public` |
| Administration | `https://sahp-idf.fr/admin/espace-medias/index.php` | `http://localhost/sahp/public/admin/espace-medias/index.php` |

La page est liée dans le pied de page (barre basse), dans le plan du site (`/plan-site`) et dans le sitemap (`/sitemap.xml`). L'administration n'est jamais indexée (balise `noindex` + en-tête `X-Robots-Tag` posé par `public/admin/.htaccess`).

## Où sont les fichiers

| Fichier | Rôle |
|---|---|
| `app/config/espace-medias.php` | Textes, catégories, taille maximale, option `noindex` |
| `app/helpers/media_kit.php` | Fonctions communes (stockage JSON verrouillé, contrôle des envois, miniatures, URLs) |
| `app/helpers/media_kit_download.php` | Téléchargements, affichage inline, ZIP, compteur |
| `app/Views/pages/espace-medias.php` | Page publique (affichée dans le layout du site) |
| `public/admin/espace-medias/index.php` | Administration |
| `public/assets/css/pages/espace-medias.css` | Styles de la page (classes `mk-`) |
| `public/assets/css/pages/admin-espace-medias.css` | Styles propres à l'administration |
| `public/assets/js/espace-medias.js` | Recherche, filtres, visionneuse, copie de lien |
| `public/admin/js/espace-medias-admin.js` | Glisser-déposer, confirmation de suppression |
| `data/espace-medias/` | **Données privées** : `media.json` + `storage/` (originaux) |
| `public/uploads/espace-medias/thumbs/` | Miniatures publiques générées automatiquement |
| `public/.user.ini` | Limites d'envoi PHP (64 Mo par fichier) |

## Mise en ligne (FTP)

1. **Envoyez les fichiers du code** listés ci-dessus, plus les fichiers modifiés du site (voir le résumé de l'intégration). Envoyez aussi les `.htaccess` et `index.php` de `data/`, `data/espace-medias/`, `data/espace-medias/storage/`, `data/security/`, `app/`, `includes/`, `public/admin/` et `public/uploads/espace-medias/thumbs/`.
2. **Ne jamais écraser ni supprimer en production** (ces dossiers ne sont pas dans Git) :
   - `data/espace-medias/` (sauf ses `.htaccess` et `index.php`) : vos fichiers et leur classement ;
   - `public/uploads/` : images du blog et miniatures de l'Espace médias ;
   - `data/security/` : compteur des tentatives de connexion ;
   - `.env`.

   Dans FileZilla, n'utilisez pas « Synchroniser / supprimer les fichiers distants absents », et refusez l'écrasement si l'un de ces dossiers apparaît dans la file d'envoi.
3. **Droits d'écriture** : clic droit sur `data/espace-medias`, puis « Droits d'accès au fichier », **755** avec « Appliquer aux sous-dossiers ». Même chose pour `data/security` et `public/uploads/espace-medias/thumbs`. Si l'envoi échoue ensuite, essayez 775. L'administration affiche un message si un de ces dossiers n'est pas inscriptible.
4. **Connectez-vous** sur `https://sahp-idf.fr/admin/login.php` avec votre compte du panneau, puis menu « Espace médias ».

## Vérification de sécurité (à faire après chaque mise en ligne)

Ces adresses doivent toutes répondre **403 Forbidden**. Le préfixe dépend de l'emplacement du dossier du site sur le FTP (en général `https://sahp-idf.fr/sahp/…` si le projet est dans un dossier `sahp/`).

- `…/data/espace-medias/media.json`
- `…/data/espace-medias/storage/`
- `…/app/config/espace-medias.php`
- `…/data/sql/admin_v1.sql`
- `https://sahp-idf.fr/uploads/espace-medias/thumbs/` (dossier des miniatures : 403, les images elles-mêmes restent visibles)
- `https://sahp-idf.fr/.user.ini`

Et ces adresses doivent répondre **404** :

- `https://sahp-idf.fr/espace-medias/telecharger?id=0000000000000000`
- le lien d'un fichier que vous avez masqué, ouvert dans une fenêtre de navigation privée.

Si une adresse « 403 » s'affiche ou se télécharge, l'hébergement n'applique pas les fichiers `.htaccess` à cet endroit : ne mettez pas de fichiers en ligne avant correction.

## Utilisation au quotidien

| Je veux… | Comment |
|---|---|
| Ajouter plusieurs fichiers d'un coup | Sélectionnez-les tous dans la zone d'envoi. Chaque fichier prend son nom de fichier comme titre. |
| Mettre à jour une plaquette sans changer le lien | « Modifier », puis « Remplacer le fichier ». Le lien de téléchargement reste identique. |
| Préparer un fichier sans le publier | Décochez « Visible » (ou bouton « Masquer »). Les visiteurs reçoivent une erreur 404 ; vous seul pouvez l'ouvrir tant que vous êtes connecté. |
| Donner une miniature à un PDF, AI ou ZIP | « Modifier », puis « Image d'aperçu » (JPG ou PNG). |
| Changer l'ordre d'affichage | « Modifier », puis « Position » (le plus petit nombre passe en premier). |
| Envoyer un fichier précis à quelqu'un | Bouton 🔗 : copie le lien de téléchargement direct. |
| Savoir ce qui est téléchargé | Compteur ⤓ à côté de chaque fichier (vos propres téléchargements, connecté, ne comptent pas). |

## Personnaliser

Tout se règle dans `app/config/espace-medias.php` : textes de la page, coordonnées, **catégories** (ajout, renommage, ordre), taille maximale des fichiers, et `noindex` (la page demande alors à Google de ne pas l'indexer et sort du sitemap). Le titre et la description Google de la page sont dans `app/helpers/seo.php` (entrée `espace-medias`).

Pour les catégories, vous pouvez changer un libellé librement, mais **ne changez pas une clé déjà utilisée** (la partie à gauche de `=>`).

## Formats acceptés

JPG, PNG, WEBP, GIF, SVG, PDF, AI, EPS, ZIP, MP4. Chaque fichier est contrôlé : extension autorisée, contenu réel vérifié (le type doit correspondre à l'extension), SVG contenant du code refusés, nom de stockage aléatoire. Les SVG sont servis avec un en-tête `Content-Security-Policy … sandbox`.

Les miniatures des PDF sont générées automatiquement seulement si l'hébergeur propose l'extension Imagick ; sinon une icône s'affiche, et vous pouvez ajouter une image d'aperçu.

## Connexion et sécurité de l'administration

- Même compte que le panneau du blog (table `admin_users`). Pour ajouter ou modifier un compte, passez par la base de données comme pour le blog.
- Après 5 échecs de connexion en 15 minutes depuis la même adresse IP, la connexion est bloquée 15 minutes. Pour débloquer immédiatement, supprimez `data/security/login-attempts.json` par FTP.
- Tous les formulaires (connexion comprise) sont protégés par un jeton CSRF.

## En cas de souci

- **« L'envoi dépasse la limite du serveur »** : envoyez moins de fichiers à la fois. Chez OVH, `public/.user.ini` fixe 64 Mo par fichier et 128 Mo par envoi ; le proxy OVH refuse de toute façon les requêtes au-delà d'environ 128 Mo, et le temps d'exécution est plafonné à 165 s. L'administration affiche les limites réellement appliquées dans la zone d'envoi. Pour vérifier qu'OVH lit bien le fichier : créez temporairement un `phpinfo()` et cherchez `user_ini.filename` et `upload_max_filesize`, puis supprimez-le.
- **En local (WAMP)**, `.user.ini` est ignoré : modifiez `upload_max_filesize` et `post_max_size` dans le `php.ini` d'Apache (icône WAMP, puis PHP, puis php.ini) et redémarrez WAMP.
- **Mot de passe oublié** : c'est celui du panneau d'administration du blog.
- **Sauvegarde** : téléchargez par FTP `data/espace-medias/` (fichiers et classement) et `public/uploads/espace-medias/thumbs/` (miniatures, régénérables en remplaçant le fichier).
