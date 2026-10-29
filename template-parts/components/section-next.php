<?php
$anchor = sanitize_title(ltrim((string) ($args['anchor'] ?? ''), '#'));
if (!$anchor) {
    return;
}
$class = sanitize_html_class($args['class'] ?? '');
?>
<a class="section-next <?= esc_attr($class); ?>" href="<?= esc_url('#' . $anchor); ?>" aria-label="<?= esc_attr__('Aller à la section suivante', 'good-vibrations'); ?>">
    <img src="<?= esc_url(get_template_directory_uri() . '/assets/medias/icons/arrow-circle-right.svg'); ?>" alt="" width="226" height="180">
</a>
