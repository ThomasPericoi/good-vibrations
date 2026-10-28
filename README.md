# Good Vibrations

Thème WordPress de VIBÉO, dans la continuité de **Vox Aedificatoris** et **Around The Wereld**, développés par Thomas Pericoi.

## Conventions et provenance

Vox sert de référence principale pour le socle. Around complète les animations et les composants ACF. Les adaptations propres à VIBÉO sont le préfixe `gv_`, le domaine `good-vibrations`, Urbanist, les logos et une palette neutre provisoire.

| Partie | Référence et adaptation |
| --- | --- |
| `functions.php` | Chargements `require_once` explicites comme les deux thèmes. |
| `inc/init.php` | Configuration, commentaires, nettoyage et chargement des ressources de Vox ; classes de catégorie et menu du blog d’Around. |
| `style.css` | Imports rangés sous les mêmes rubriques que Vox. Reset et compatibilité WordPress chargés séparément. |
| `base/base.css`, `typography.css`, `content.css` | Repris à l’identique de Vox. |
| `base/animations.css` | Repris à l’identique d’Around : `fadeIn` et `slideIn`. |
| Conteneurs, colonnes et grilles | Repris à l’identique de Vox, avec ses dimensions et breakpoints. |
| Variables | Noms, espacements et breakpoints de Vox ; police Urbanist et couleurs neutres. |
| Boutons | Classes `.btn`, `.btn-primary`, `.btn-black`, `.btn-icon-arrow-right` de Vox. Variante `.btn-outline` conservée pour les boutons déjà créés dans cette base. |
| Header et JavaScript | Structure, sélecteurs et fonctions d’initialisation de Vox. En-têtes simples, sans composant hero. |
| Pages et listes | Structure en-tête/contenu, classes et balisage issus de Vox ; aucun contenu client recopié. |
| Rendu des CTA et ACF | Composant clonable simplifié : couleur et lien uniquement. |
| White label | Logo et favicon VIBÉO, crédits Thomas Pericoi. |

Les ajouts nécessaires restent localisés : navigation disponible sans JavaScript, boucle de focus du menu mobile, contrôle des liens incomplets et fonctionnement des templates sans ACF. La classe `js-enabled` est ajoutée au démarrage de `main.js` plutôt que dans le head pour laisser le contenu visible si le script ne charge pas.

## Architecture

```text
functions.php
inc/
  init.php                   Supports, menus, commentaires, nettoyage, CSS/JS
  admin.php                  Page des options du thème
  render.php                 Helpers de rendu et champs facultatifs
  acf.php                    JSON local ACF
  acf-json/                  Options générales, composant bouton, bloc bouton
  blocks.php                 Enregistrement des blocs et catégorie VIBÉO
  blocks/button/             Métadonnées et template du bouton
  post-types.php             Enregistrement des types de contenus
  post-types/realisation.php Réalisations et catégories
  contact-form.php           Intégration facultative Contact Form 7
  white-label.php            Connexion, barre admin et crédits
assets/
  css/
    inc/                     Reset et compatibilité WordPress
    base/                    Imports, variables, animations, base, typo, contenu
    elements/                Boutons, colonnes, conteneurs, header/footer, etc.
    blocks/                  Styles des blocs
    template-parts/          Styles des composants PHP
    templates/               Futurs modèles de pages
    pages/                   Styles des templates WordPress
  fonts/aftika/               Sept graisses locales fournies
  js/main.js                 Initialisations communes
  js/ascii-printer.min.js     Signature console reprise de Vox
  medias/                    Logos VIBÉO, icônes, images et vidéos
header.php / footer.php       Structure commune
page.php                     En-tête et contenu des pages
index.php                    Articles, archives, recherche et réalisations
404.php / searchform.php      Page introuvable et recherche
template-parts/              Carte d’article et état vide
templates/                   Futurs modèles de pages
languages/                    Futures traductions
```

## Installation

WordPress 6.5 minimum et PHP 8.1 minimum. ACF Pro fournit les options et le bloc bouton ; le rendu des templates publics fonctionne aussi sans ACF. Rank Math et Contact Form 7 restent facultatifs.

1. Activer **Good Vibrations**.
2. Renseigner le nom du site et le logo dans WordPress.
3. Affecter les menus à `header-menu` et `footer-submenu` (un niveau).
4. Choisir si besoin une page d’accueil statique dans Réglages → Lecture. Aucun `front-page.php` ne force encore une mise en page spécifique.
5. Après la désactivation des URL des réalisations, enregistrer une fois Réglages → Permaliens pour retirer les anciennes règles.
6. Dans ACF, synchroniser les groupes proposés si une version antérieure était déjà enregistrée en base.

Le nom du bloc `acf/gv-button`, les clés des champs lien/couleur sont conservés pour ne pas casser le contenu de la première base. Le bouton utilise désormais le composant clonable d’Around avec le préfixe `block`.

## CSS et JavaScript

Ajouter les CSS dans les rubriques de `style.css`, comme dans les thèmes de référence. `gv_get_stylesheet_version()` utilise la date la plus récente des CSS pour versionner la feuille principale. `gv_get_asset_version()` versionne les ressources individuelles.

Conventions disponibles : `.container`, `.container-sm`, `.container-lg`, `.cols-wrapper`, `.col`, `.grid`, `.grid-2`, `.grid-3`, `.grid-4`, `.formatted`, `.btn-wrapper`, `.btn` et `.h1-size` à `.h6-size`.

Le JavaScript conserve les fonctions de Vox : `initLayoutVariables`, `initSignature`, `initRevealOnScroll`, `initOrderedLists` et `initMenu`. Les sections directement sous `#main` utilisent `.js-inView` et peuvent être exclues avec `.js-alwaysInView`. Aucun `data-reveal` ni animation `gv-reveal` n’est utilisé. Le respect du mouvement réduit est géré dans `base.css` et les initialisations concernées.

Urbanist est chargée via Google Fonts dans `inc/init.php`, sur le site, avec les graisses 100 à 900 et les italiques. Les fichiers Aftika fournis sont conservés mais ne sont plus chargés.

## PHP et contenus

- **Réalisations** : type `realisation`, taxonomie `realisation_category`, contenus administrables destinés à la page d’accueil, sans single ni archive publique. Éditeur et API REST conservés pour l’administration ; exclusion des recherches et menus publics. Les catégories restent disponibles pour le classement interne.
- **Commentaires** : fermés et masqués, sans suppression des données existantes. Réglages regroupés dans `inc/init.php`, comme dans les références.
- **Options du thème** : page ACF accessible avec `edit_pages`, contacts, logo, adresse et copyright du footer raccordés au template. Aucun contenu prérempli.
- **CTA** : `gv_get_cta()` / `gv_render_cta()` lisent uniquement `cta_color` et `cta_text`. Couleurs : `primary`, `black`, `white`. Les nouveaux onglets reçoivent `noopener noreferrer`.
- **Images** : `wp_get_attachment_image()` utilise directement les IDs des champs ACF et conserve le rendu responsive natif.
- **Champs ACF** : appels directs à `get_field()` dans les templates, protégés si ACF est absent.
- **Fil d’Ariane** : `gv_render_breadcrumbs()` utilise Rank Math lorsqu’il est disponible.
- **Contact Form 7** : balisage automatique désactivé comme dans les références, sans imposer le plugin.

Les méthodes XML-RPC authentifiées sont désactivées comme dans Vox ; cela ne bloque pas tout le point d’accès XML-RPC. Les rôles ne sont pas supprimés. Aucun slider, carte, lecteur PDF ou champ métier d’un ancien client n’est importé. Les styles natifs Gutenberg restent chargés.

## Vérification

Syntaxe PHP/JavaScript, références CSS/polices, unicité des clés ACF et templates des blocs vérifiés. Tests ciblés des helpers et du modèle de réalisations ; tests JavaScript simulant mobile, desktop, clavier, absence de menu et absence d’IntersectionObserver. Les fichiers de base indiqués comme identiques ont été comparés aux références.

Le rendu dans WordPress et la synchronisation/prévisualisation ACF restent à vérifier dans l’instance locale. Le screenshot préexistant est conservé et devra être remplacé avec le design final.

Projet privé développé pour VIBÉO. Tous droits réservés.

## Footer — copie Figma Make

Le footer reprend le fond sombre, les contacts et leurs traits dorés, le séparateur, puis l’identité et les liens légaux de la capture. Les espacements desktop proviennent de la capture à 1061 px ; les règles mobiles adaptent les colonnes en pile.

Synchroniser le groupe **Theme options - Footer** dans ACF, puis renseigner **Options du thème** : contacts (intitulé et e-mail), logo alternatif facultatif de la médiathèque, adresse et texte du copyright. L’année est automatique. Affecter le menu des liens légaux à **Footer submenu**. Les champs sont vides par défaut ; un footer entièrement vide n’est pas rendu. Aucune option n’a été écrite en base de données. L’ancien champ de test `gv_footer_text` n’est plus utilisé ; ses éventuelles données en base ne sont pas supprimées.

Le footer utilise par défaut le logo du site (Identité du site), passé en blanc avec `brightness(0) invert(1)`. Le champ ACF « Logo alternatif du pied de page » le remplace sans filtre. Sans aucun logo valide, aucun emplacement vide n’est affiché.

## Règles graphiques VIBÉO

La palette (noir, sable, gris brume), la couleur du texte secondaire et les bordures sont centralisées dans `base/variables.css`. `base/typography.css` définit Urbanist, les grands titres légers et les tailles responsive. Les surtitres partagent `.eyebrow`. `base/content.css` distingue les petits intertitres éditoriaux dans `.formatted` des grands titres de section. Les séparateurs restent propres au template de page. Ces règles adaptent désormais le socle initial de Vox au design VIBÉO ; les mentions de copies identiques ci-dessus décrivent l’état initial du boilerplate.

## Menu principal

Affecter les liens principaux à **Header** (`header-menu`) et les liens complémentaires à **Header submenu** (`header-submenu`). Le footer conserve son emplacement indépendant. Synchroniser **Theme options - Header** dans ACF pour renseigner le bouton, cloné depuis le composant bouton avec le préfixe `header`. Aucun lien ni libellé client n’est prérempli.

Le menu s’ouvre en plein écran sur toutes les tailles, avec numérotation CSS, fond dégradé et bouton de fermeture. Le lien survolé (ou ciblé au clavier) devient blanc et se décale légèrement. La page courante utilise les classes natives WordPress et `aria-current="page"` pour un lien blanc et gras ; aucun lien n’est mis en évidence en permanence par sa position dans le menu. Sans JavaScript, les liens restent visibles. Avec JavaScript : Échap, boucle de focus et arrière-plan inerte pendant l’ouverture.

## Site sans blog

Les articles natifs sont désactivés dans `inc/init.php` (administration, recherche, menus, REST et requêtes publiques du blog). Les raccourcis Articles et Brouillon rapide sont retirés. Aucun article existant n’est supprimé de la base. Les pages et le type Réalisations sont conservés, sans page individuelle pour les réalisations. `index.php` reste le template de repli WordPress pour les archives et recherches ; ses cartes génériques utilisent `template-parts/item-content.php`.

Google Fonts utilise `wp_enqueue_style()` avec une dépendance explicite de la feuille principale, `display=swap` et des préconnexions via `wp_resource_hints`. Le chargement reste limité au site public.

Les règles CSS sont mobile-first : breakpoints en `min-width` uniquement, et chaque adaptation responsive suit sa règle de base. Les conditions de survol et de réduction des animations sont conservées.
