# Good Vibrations

Custom WordPress theme developed for **VIBÉO**.

## Features

* Advanced Custom Fields (ACF) Pro integration with local JSON field groups
* Static front page assembled from reusable template parts
* Custom **Réalisations** content type with internal categories
* Reusable ACF button block and theme-wide CTA component
* Responsive, keyboard-operable navigation with a no-JavaScript fallback
* Reduced-motion-aware section reveals and introduction video
* Modular CSS architecture with automatic cache busting
* Urbanist typography from Google Fonts
* Optional Rank Math breadcrumbs and Contact Form 7 integration

## Architecture

### CSS

* `assets/css/inc`: reset and WordPress core compatibility
* `assets/css/base`: variables, animations, typography and formatted content
* `assets/css/elements`: reusable layout and interface styles
* `assets/css/blocks`: ACF block styles
* `assets/css/template-parts`: reusable component styles
* `assets/css/templates`: reserved for custom page template styles
* `assets/css/pages`: WordPress template styles

Feature CSS modules are imported from `style.css`; the reset and WordPress compatibility styles are enqueued separately. The stylesheet version uses the newest CSS file timestamp, so changes invalidate the browser cache.

### JavaScript

`assets/js/main.js` contains the global theme behaviours:

* responsive menu, keyboard controls and accessible hidden states
* viewport, header and admin bar CSS variables
* reduced-motion-aware section reveals and introduction video handling
* ordered-list start preservation
* theme signature

Feature-specific behaviour is organized in dedicated initializer functions.

### PHP

* `inc`: theme setup, integrations, rendering helpers and content types
* `inc/acf-json`: local ACF field groups for pages, theme options and blocks
* `inc/blocks`: ACF block registration and templates
* `inc/post-types`: custom post type declarations
* `template-parts`: reusable front-end components

## Requirements

* WordPress 6.5 or later
* PHP 8.1 or later
* Advanced Custom Fields Pro for ACF-managed content, theme options and the button block

Rank Math and Contact Form 7 are optional.

## Setup

1. Activate **Good Vibrations** and install ACF Pro.
2. Select a static front page in **Settings → Reading**. Its page fields provide the ACF context for `front-page.php`.
3. Sync the local ACF field groups if prompted.
4. Assign menus to **Header**, **Header submenu** and **Footer submenu**.

## Repository Notes

This repository contains the theme source and selected VIBÉO brand assets. It does not include a WordPress database, production content, credentials or third-party plugin code.

The built-in WordPress post type is disabled without deleting existing posts. **Réalisations** remain manageable in the dashboard and are not publicly queryable.

## Verification

No automated test suite or lint configuration is bundled with the theme. PHP and JavaScript syntax, ACF JSON files and CSS import paths can be checked locally. Front-end rendering and ACF synchronization should be verified in a WordPress installation.

## License

Private project developed for VIBÉO.
All rights reserved.

## Accueil — agence

Synchroniser le groupe **Page type - Front page** dans ACF. Les deux parties utilisent les préfixes `agency_1` et `agency_2` : titre, sous-titre, deux boutons clonés et ancre suivante.

`front-page-agency.php` appelle deux fois `template-parts/components/editorial-section.php`, avec les ancres `agency-1` et `agency-2`. Tous les champs sont lus à partir du préfixe. `template-parts/components/editorial-section.css` porte les styles communs ; `front-page-agency.css` inverse le dégradé de la première partie.

La flèche de l’introduction pointe vers `agency-1`, puis celle de la première partie vers `agency-2`. La dernière ancre reste à renseigner lorsqu’une section suivante existe.
