# Guide d'optimisation pour la production (Infomaniak)

## Problèmes identifiés et corrigés

### ✅ Optimisations appliquées

1. **Session conditionnelle** - La session n'est démarrée que pour les pages qui en ont besoin (formulaires)
2. **Cache HTML optimisé** - TTL de 24h en production, headers ETag et Last-Modified
3. **Cache navigateur amélioré** - Headers Cache-Control optimisés avec stale-while-revalidate
4. **Compression GZIP** - Activée pour tous les fichiers texte
5. **Cache des assets** - CSS/JS/images mis en cache 1 an avec versioning

## Configuration Infomaniak

### 1. Activer OPcache (CRITIQUE pour la performance)

Créez un fichier `.user.ini` à la racine de votre site avec :

```ini
; OPcache - Cache des fichiers PHP compilés
opcache.enable=1
opcache.enable_cli=0
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.validate_timestamps=0
```

**Important** : `opcache.validate_timestamps=0` désactive la vérification des fichiers modifiés. 
Après chaque mise à jour, videz le cache via le panneau Infomaniak ou redémarrez PHP.

### 2. Vérifier les permissions du dossier cache

Assurez-vous que le dossier `data/cache/html` a les bonnes permissions :

```bash
chmod 755 data/cache/html
```

### 3. Configuration de la variable APP_ENV

Dans `app/config/config.php`, changez :

```php
define('APP_ENV', false); // false = production
```

Cela activera :
- Cache de 24h au lieu de 1h
- Désactivation du debug SMTP
- URLs sans `/sahp/public`

## Vérifications de performance

### Tester le cache

1. Visitez votre site
2. Vérifiez les headers HTTP avec les outils développeur (F12)
3. Vous devriez voir :
   - `X-Cache: HIT` pour les pages en cache
   - `Cache-Control: public, max-age=86400`
   - `ETag` présent

### Outils de test

- **PageSpeed Insights** : https://pagespeed.web.dev/
- **GTmetrix** : https://gtmetrix.com/
- **Pingdom** : https://tools.pingdom.com/

## Optimisations supplémentaires recommandées

### 1. CDN pour les assets statiques

Considérez utiliser un CDN pour :
- Les polices Google Fonts (déjà optimisé)
- Les images (si beaucoup de trafic)

### 2. Minification CSS/JS

Pour aller plus loin, minifiez vos fichiers CSS et JS en production.

### 3. Lazy loading des images

Ajoutez `loading="lazy"` aux images dans vos templates.

### 4. Préchargement des ressources critiques

Ajoutez dans `<head>` :
```html
<link rel="preload" href="/assets/css/style.css" as="style">
<link rel="preconnect" href="https://fonts.googleapis.com">
```

## Monitoring

Surveillez :
- Temps de réponse serveur (< 200ms idéal)
- Taux de cache HIT (> 80% idéal)
- Taille des pages HTML (< 200KB idéal)

## Purge du cache

Pour vider le cache manuellement :
```
https://votre-site.com/public/purge-cache.php?token=CHANGE_ME_SECRET_TOKEN
```

**Important** : Changez le token dans `purge-cache.php` !
