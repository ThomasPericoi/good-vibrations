<?php
/* CONTACT FORM
--------------------------------------------------------------- */

// Remove <p> and <br/> from Contact Form 7
add_filter('wpcf7_autop_or_not', '__return_false');

// Warn admins when Contact Form 7 is unavailable.
function gv_display_cf7_missing_notice()
{
    if (defined('WPCF7_VERSION') || !current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-warning"><p><strong>' . esc_html__('Contact Form 7 est requis pour afficher le formulaire de téléchargement du portfolio.', 'good-vibrations') . '</strong> ' . esc_html__('Installez et activez le plugin, puis sélectionnez le formulaire dans les champs de la page d’accueil.', 'good-vibrations') . '</p></div>';
}
add_action('admin_notices', 'gv_display_cf7_missing_notice');
