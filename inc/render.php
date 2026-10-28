<?php
/* RENDER
--------------------------------------------------------------- */
// Build a reusable CTA from cloned ACF button fields.
function gv_get_cta($selector_prefix, $post_id = false, $sub_field = false)
{
    if (!function_exists('get_field') || ($sub_field && !function_exists('get_sub_field'))) {
        return '';
    }

    $color = $sub_field ? get_sub_field("{$selector_prefix}_cta_color") : get_field("{$selector_prefix}_cta_color", $post_id);
    $text_cta = $sub_field ? get_sub_field("{$selector_prefix}_cta_text") : get_field("{$selector_prefix}_cta_text", $post_id);

    if (!is_array($text_cta) || empty($text_cta['title']) || empty($text_cta['url']) || !esc_url($text_cta['url'])) {
        return '';
    }

    $color = in_array($color, array('primary', 'black', 'white'), true) ? $color : 'primary';
    $target = ($text_cta['target'] ?? '') === '_blank' ? '_blank' : '_self';
    $rel = $target === '_blank' ? ' rel="noopener noreferrer"' : '';

    return sprintf(
        '<a class="btn btn-%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
        esc_attr($color),
        esc_url($text_cta['url']),
        esc_attr($target),
        $rel,
        esc_html($text_cta['title'])
    );
}

// Echo a reusable CTA from cloned ACF button fields.
function gv_render_cta($selector_prefix, $post_id = false, $sub_field = false)
{
    echo gv_get_cta($selector_prefix, $post_id, $sub_field);
}

// Render optional Rank Math breadcrumbs.
function gv_render_breadcrumbs()
{
    if (function_exists('rank_math_the_breadcrumbs')) {
        echo '<div class="breadcrumbs">';
        rank_math_the_breadcrumbs();
        echo '</div>';
    }
}
