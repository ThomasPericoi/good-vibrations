<?php
/* ADMIN
--------------------------------------------------------------- */

// Add the global theme options page for ACF fields.
add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(array(
        'page_title'      => __('Options du thème', 'good-vibrations'),
        'menu_title'      => __('Options du thème', 'good-vibrations'),
        'menu_slug'       => 'theme-general-settings',
        'capability'      => 'edit_pages',
        'redirect'        => false,
        'position'        => 2,
        'icon_url'        => 'dashicons-admin-settings',
        'update_button'   => __('Mettre à jour', 'good-vibrations'),
        'updated_message' => __('Tout est en ordre', 'good-vibrations'),
    ));
});

// Remove native blog shortcuts from the administration.
function gv_remove_posts_admin_menu()
{
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'gv_remove_posts_admin_menu');

function gv_remove_posts_admin_bar($admin_bar)
{
    $admin_bar->remove_node('new-post');
}
add_action('admin_bar_menu', 'gv_remove_posts_admin_bar', 100);

function gv_remove_posts_dashboard_widgets()
{
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'gv_remove_posts_dashboard_widgets');
