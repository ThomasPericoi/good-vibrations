<?php
$anchor = sanitize_title(ltrim((string) ($args['anchor'] ?? ''), '#'));
if (!$anchor) {
    return;
}
$class = sanitize_html_class($args['class'] ?? '');
$color = ($args['color'] ?? 'dark') === 'light' ? 'light' : 'dark';
$previous = !empty($args['previous']);
?>
<a class="section-next section-next-<?= esc_attr($color); ?> <?= esc_attr($class); ?><?= $previous ? ' section-previous' : ''; ?>" href="<?= esc_url('#' . $anchor); ?>" aria-label="<?= esc_attr($previous ? __('Aller à la section précédente', 'good-vibrations') : __('Aller à la section suivante', 'good-vibrations')); ?>">
    <img src="<?= esc_url(get_template_directory_uri() . '/assets/medias/icons/arrow-circle-right.svg'); ?>" alt="" width="226" height="180">
</a>
