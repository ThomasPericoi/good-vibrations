<?php
if (!function_exists('get_field') || empty($args['prefix'])) {
    return;
}

$post_id = $args['post_id'] ?? get_the_ID();
$prefix = $args['prefix'];
$title = get_field($prefix . '_title', $post_id);
$subtitle = get_field($prefix . '_subtitle', $post_id);
$primary_cta = gv_get_cta($prefix . '_primary', $post_id);
$secondary_cta = gv_get_cta($prefix . '_secondary', $post_id);
$anchor = sanitize_title(ltrim((string) ($args['anchor'] ?? ''), '#'));
$next_anchor = get_field($prefix . '_next_anchor', $post_id);
$section_class = sanitize_html_class($args['class'] ?? '');

if (!$title && !$subtitle && !$primary_cta && !$secondary_cta) {
    return;
}
?>

<section<?php if ($anchor): ?> id="<?= esc_attr($anchor); ?>" <?php endif; ?>
    class="editorial-section <?= esc_attr($section_class); ?>" tabindex="-1">
    <div class="container container-lg editorial-content">
        <div class="editorial-text">
            <?php if ($title): ?>
                <h2 class="editorial-title"><?= wp_kses($title, array('br' => array())); ?></h2>
            <?php endif; ?>
            <?php if ($subtitle): ?>
                <p class="editorial-subtitle"><?= wp_kses($subtitle, array('br' => array())); ?></p>
            <?php endif; ?>
            <?php if ($primary_cta || $secondary_cta): ?>
                <div class="btn-wrapper editorial-actions">
                    <?php if ($primary_cta): ?>
                        <div class="editorial-primary"><?= $primary_cta; ?></div>
                    <?php endif; ?>
                    <?php if ($secondary_cta): ?>
                        <div class="editorial-secondary"><?= $secondary_cta; ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php get_template_part('template-parts/components/section-next', null, array('anchor' => $next_anchor)); ?>
    </div>
</section>
