<?php
$post_id = $args['post_id'];
$title = get_field('download_title', $post_id);
$subtitle = get_field('download_subtitle', $post_id);
$background = get_field('download_background', $post_id);
$notice = get_field('download_notice', $post_id);
$form_id = (int) get_field('download_form', $post_id);
$next_anchor = sanitize_title(ltrim((string) get_field('download_next_anchor', $post_id), '#'));
$form = '';
if ($form_id && get_post_type($form_id) === 'wpcf7_contact_form' && shortcode_exists('contact-form-7')) {
    $form = do_shortcode('[contact-form-7 id="' . $form_id . '"]');
}

if (!$title && !$subtitle && !$form) {
    return;
}
?>

<!-- Download -->
<section id="download" class="editorial-section front-page-download editorial-background-<?= esc_attr($background); ?> <?= $next_anchor ? 'has-section-next' : ''; ?>">
    <div class="container container-lg editorial-content">
        <div class="editorial-text download-content">
        <?php if ($title): ?>
            <h2 class="editorial-title"><?= wp_kses($title, array('br' => array())); ?></h2>
        <?php endif; ?>
        <?php if ($subtitle): ?>
            <p class="editorial-subtitle"><?= wp_kses($subtitle, array('br' => array())); ?></p>
        <?php endif; ?>
        <?php if ($form): ?>
            <div class="form-on-background">
                <?php if ($notice): ?>
                    <p class="form-notice"><?= esc_html($notice); ?></p>
                <?php endif; ?>
                <?= $form; ?>
            </div>
        <?php endif; ?>
        </div>
    </div>
    <?php get_template_part('template-parts/components/section-next', null, array(
        'anchor' => $next_anchor,
        'color' => 'dark',
    )); ?>
</section>
