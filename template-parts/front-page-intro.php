<?php
$post_id = $args['post_id'] ?? get_the_ID();
if (!function_exists('get_field')) {
    return;
}

$video_id = (int) get_field('intro_video', $post_id);
$video_url = $video_id ? wp_get_attachment_url($video_id) : '';
$video_type = $video_id ? get_post_mime_type($video_id) : '';
$logo_id = (int) (get_field('intro_logo', $post_id) ?: get_theme_mod('custom_logo'));
$logo = $logo_id ? wp_get_attachment_image($logo_id, 'full', false, array(
    'class' => 'intro-logo',
    'loading' => 'eager',
    'alt' => get_post_meta($logo_id, '_wp_attachment_image_alt', true) ?: get_bloginfo('name'),
)) : '';
$next_anchor = get_field('intro_next_anchor', $post_id);

if (!$video_url && !$logo) {
    return;
}
?>

<!-- Introduction -->
<section id="accueil" class="front-page-intro js-alwaysInView">
    <?php if ($video_url && strpos((string) $video_type, 'video/') === 0): ?>
        <video class="intro-video" muted loop playsinline autoplay aria-hidden="true"
            src="<?= esc_url($video_url); ?>">
        </video>
    <?php endif; ?>

    <div class="container container-lg intro-content">
        <?php if ($logo): ?>
            <h1 class="intro-title"><?= $logo; ?></h1>
        <?php else: ?>
            <h1 class="screen-reader-text"><?= esc_html(get_the_title($post_id)); ?></h1>
        <?php endif; ?>

        <?php get_template_part('template-parts/components/section-next', null, array(
            'anchor' => $next_anchor,
            'class' => 'intro-next',
        )); ?>
    </div>
</section>
