<?php
$post_id = $args['post_id'];
$title = get_field('project_title', $post_id);
$subtitle = get_field('project_subtitle', $post_id);
$name = get_field('project_name', $post_id);
$label = get_field('project_label', $post_id);
$text = get_field('project_text', $post_id);
$cta = gv_get_cta('project_primary', $post_id);
$background = get_field('project_background', $post_id);
$color = get_field('project_arrow', $post_id);
$next_anchor = sanitize_title(ltrim((string) get_field('project_next_anchor', $post_id), '#'));
$images = array();
foreach ((array) get_field('project_images', $post_id) as $row) {
    if (!empty($row['project_image'])) {
        $images[] = $row['project_image'];
    }
}
if (!$title && !$subtitle && !$name && !$label && !$text && !$cta && !$images) {
    return;
}
$intro_anchor = $images ? 'project-slide-1' : $next_anchor;
?>

<!-- Project -->
<section id="project"
    class="front-page-project editorial-background-<?= esc_attr($background); ?> js-alwaysInView">
    <div class="portfolio-track project-track" tabindex="0"
        aria-label="<?= esc_attr__('Diaporama du projet', 'good-vibrations'); ?>">
        <section id="project-intro"
            class="portfolio-slide portfolio-intro project-intro <?= $intro_anchor ? 'has-section-next' : ''; ?>">
            <div class="portfolio-content">
                <?php if ($title || $subtitle): ?>
                    <div class="editorial-text">
                        <?php if ($title): ?>
                            <h2 class="editorial-title"><?= wp_kses($title, array('br' => array())); ?></h2>
                        <?php endif; ?>
                        <?php if ($subtitle): ?>
                            <p class="editorial-subtitle"><?= wp_kses($subtitle, array('br' => array())); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="project-description">
                    <?php if ($name): ?>
                        <p class="project-name h5-size"><?= esc_html($name); ?></p><?php endif; ?>
                    <?php if ($label): ?>
                        <p class="project-label h5-size"><?= esc_html($label); ?></p><?php endif; ?>
                    <?php if ($text): ?>
                        <div class="project-text"><?= wp_kses_post(wpautop($text)); ?></div>
                    <?php endif; ?>
                    <?php if ($cta): ?>
                        <div class="btn-wrapper"><?= $cta; ?></div><?php endif; ?>
                </div>
            </div>
            <?php get_template_part('template-parts/components/section-next', null, array('anchor' => $intro_anchor, 'color' => $color)); ?>
        </section>
        <?php foreach ($images as $index => $image): ?>
            <?php $anchor = $index + 1 === count($images) ? $next_anchor : ''; ?>
            <section id="project-slide-<?= esc_attr($index + 1); ?>"
                class="portfolio-slide project-image <?= $anchor ? 'has-section-next' : ''; ?>">
                <?= wp_get_attachment_image($image, 'full'); ?>
                <?php get_template_part('template-parts/components/section-next', null, array('anchor' => $anchor, 'color' => $color)); ?>
            </section>
        <?php endforeach; ?>
    </div>
</section>
