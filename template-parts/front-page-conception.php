<?php
$post_id = $args['post_id'];
$image_id = get_field('conception_image', $post_id);
$title = get_field('conception_title', $post_id);
$text = get_field('conception_text', $post_id);
$services = get_field('conception_services', $post_id);
$next_anchor = get_field('conception_next_anchor', $post_id);

if (!$image_id && !$title && !$text && !$services) {
    return;
}
?>

<section id="conception" class="front-page-conception" tabindex="-1">
    <?php if ($image_id): ?>
        <div class="conception-media">
            <?= wp_get_attachment_image($image_id, 'full', false, array('sizes' => '(min-width: 992px) 31vw, 100vw')); ?>
        </div>
    <?php endif; ?>
    <div class="conception-content">
        <?php if ($title): ?>
            <h2 class="conception-title"><?= wp_kses($title, array('br' => array())); ?></h2>
        <?php endif; ?>
        <div class="conception-columns">
            <?php if ($text): ?>
                <div class="conception-text"><?= wp_kses($text, array('br' => array())); ?></div>
            <?php endif; ?>
            <?php if ($services): ?>
                <ul class="conception-services">
                    <?php foreach ($services as $service): ?>
                        <?php if ($service['conception_service_title']): ?>
                            <li>
                                <span><?= esc_html($service['conception_service_title']); ?></span>
                                <?php if ($service['conception_service_detail']): ?>
                                    <small><?= esc_html($service['conception_service_detail']); ?></small>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
    <?php get_template_part('template-parts/components/section-next', null, array(
            'anchor' => $next_anchor,
            'color' => 'dark',
        )); ?>
</section>
