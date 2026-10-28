<?php
/* ACF
--------------------------------------------------------------- */

// Save ACF local JSON inside the theme.
function gv_save_acf_groups_json($path)
{
    return get_stylesheet_directory() . '/inc/acf-json';
}
add_filter('acf/settings/save_json', 'gv_save_acf_groups_json');

// Load ACF local JSON from the theme.
function gv_load_acf_groups_json($paths)
{
    $paths[] = get_stylesheet_directory() . '/inc/acf-json';
    return array_unique($paths);
}
add_filter('acf/settings/load_json', 'gv_load_acf_groups_json');

// Keep exported ACF JSON filenames readable and stable.
function gv_name_acf_groups_json($filename, $post, $load_path)
{
    $filenames = array(
        'group_gv_page_front_page' => 'group_gv_page_front_page',
        'group_gv_block_button' => 'group_gv_block_button',
        'group_gv_component_button' => 'group_gv_component_button',
        'group_gv_page' => 'group_gv_page',
        'group_gv_theme_options' => 'group_gv_theme_options',
        'group_gv_theme_options_header' => 'group_gv_theme_options_header',
    );

    if (!empty($post['key']) && !empty($filenames[$post['key']])) {
        return $filenames[$post['key']] . '.json';
    }

    return $filename;
}
add_filter('acf/json/save_file_name', 'gv_name_acf_groups_json', 10, 3);

// Warn admins when the required ACF Pro plugin is missing.
function gv_display_acf_missing_notice()
{
    if ((defined('ACF_PRO') && ACF_PRO) || !current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-error"><p><strong>' . esc_html__('Advanced Custom Fields Pro est requis par le thème Good Vibrations.', 'good-vibrations') . '</strong> ' . esc_html__('Activez le plugin pour administrer et afficher les contenus personnalisés.', 'good-vibrations') . '</p></div>';
}
add_action('admin_notices', 'gv_display_acf_missing_notice');
