<?php
defined('ABSPATH') || exit;

$taxonomy_labels = [
    'name' => __('Catégories de réalisation', 'good-vibrations'),
    'singular_name' => __('Catégorie de réalisation', 'good-vibrations'),
    'menu_name' => __('Catégories', 'good-vibrations'),
    'all_items' => __('Toutes les catégories', 'good-vibrations'),
    'edit_item' => __('Modifier la catégorie', 'good-vibrations'),
    'view_item' => __('Voir la catégorie', 'good-vibrations'),
    'update_item' => __('Mettre à jour la catégorie', 'good-vibrations'),
    'add_new_item' => __('Ajouter une catégorie', 'good-vibrations'),
    'new_item_name' => __('Nom de la nouvelle catégorie', 'good-vibrations'),
    'search_items' => __('Rechercher des catégories', 'good-vibrations'),
    'not_found' => __('Aucune catégorie trouvée.', 'good-vibrations'),
];

register_taxonomy('realisation_category', ['realisation'], [
    'labels' => $taxonomy_labels,
    'public' => false,
    'publicly_queryable' => false,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    'show_admin_column' => true,
    'show_in_rest' => true,
    'query_var' => false,
    'rewrite' => false,
]);

$labels = [
    'name' => __('Réalisations', 'good-vibrations'),
    'singular_name' => __('Réalisation', 'good-vibrations'),
    'menu_name' => __('Réalisations', 'good-vibrations'),
    'add_new' => __('Ajouter', 'good-vibrations'),
    'add_new_item' => __('Ajouter une réalisation', 'good-vibrations'),
    'edit_item' => __('Modifier la réalisation', 'good-vibrations'),
    'new_item' => __('Nouvelle réalisation', 'good-vibrations'),
    'view_item' => __('Voir la réalisation', 'good-vibrations'),
    'view_items' => __('Voir les réalisations', 'good-vibrations'),
    'search_items' => __('Rechercher des réalisations', 'good-vibrations'),
    'not_found' => __('Aucune réalisation trouvée.', 'good-vibrations'),
    'not_found_in_trash' => __('Aucune réalisation dans la corbeille.', 'good-vibrations'),
    'all_items' => __('Toutes les réalisations', 'good-vibrations'),
    'archives' => __('Archives des réalisations', 'good-vibrations'),
    'featured_image' => __('Image principale', 'good-vibrations'),
    'set_featured_image' => __('Définir l’image principale', 'good-vibrations'),
    'remove_featured_image' => __('Retirer l’image principale', 'good-vibrations'),
];

register_post_type('realisation', [
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    'show_in_menu' => true,
    'exclude_from_search' => true,
    'show_in_rest' => true,
    'query_var' => false,
    'hierarchical' => false,
    'capability_type' => 'post',
    'supports' => [
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'revisions',
        'page-attributes',
    ],
    'taxonomies' => ['realisation_category'],
    'has_archive' => false,
    'rewrite' => false,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-portfolio',
]);
