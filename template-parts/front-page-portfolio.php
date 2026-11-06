<?php
$post_id = $args['post_id'];
$title = get_field('portfolio_title', $post_id);
$intro_cta = gv_get_cta('portfolio_intro', $post_id);
$projects = get_field('portfolio_projects', $post_id);
$closing_image = get_field('portfolio_closing_image', $post_id);
$closing_cta = gv_get_cta('portfolio_closing', $post_id);
$next_anchor = get_field('portfolio_next_anchor', $post_id);
$slides = array();

if ($title || $intro_cta) {
    $slides[] = array('type' => 'intro', 'title' => $title, 'label' => '', 'image' => 0, 'cta' => $intro_cta, 'color' => get_field('portfolio_intro_arrow', $post_id));
}
if ($projects) {
    foreach ($projects as $project) {
        if (!$project['portfolio_project_image'] && !$project['portfolio_project_title'] && !$project['portfolio_project_label']) {
            continue;
        }
        $slides[] = array(
            'type' => 'project',
            'title' => $project['portfolio_project_title'],
            'label' => $project['portfolio_project_label'],
            'image' => $project['portfolio_project_image'],
            'cta' => '',
            'color' => $project['portfolio_project_arrow'],
        );
    }
}
if ($closing_image || $closing_cta) {
    $slides[] = array('type' => 'closing', 'title' => '', 'label' => '', 'image' => $closing_image, 'cta' => $closing_cta, 'color' => get_field('portfolio_closing_arrow', $post_id));
}
if (!$slides) {
    return;
}
?>

<!-- Portfolio -->
<section id="portfolio" class="front-page-portfolio js-alwaysInView">
    <div class="portfolio-track" tabindex="0"
        aria-label="<?= esc_attr__('Diaporama du portfolio', 'good-vibrations'); ?>">
        <?php foreach ($slides as $index => $slide): ?>
            <?php $image_url = $slide['image'] ? wp_get_attachment_image_url($slide['image'], 'full') : ''; ?>
            <?php $slide_next_anchor = sanitize_title(ltrim((string) ($index + 1 < count($slides) ? 'portfolio-slide-' . ($index + 2) : $next_anchor), '#')); ?>
            <section id="portfolio-slide-<?= esc_attr($index + 1); ?>"
                class="portfolio-slide portfolio-<?= esc_attr($slide['type']); ?> <?= $slide_next_anchor ? 'has-section-next' : ''; ?>" <?php if ($image_url): ?>
                    style="<?= esc_attr('background-image: url("' . esc_url($image_url) . '");'); ?>" <?php endif; ?>
                aria-label="<?= esc_attr(sprintf(__('Diapositive %1$d sur %2$d', 'good-vibrations'), $index + 1, count($slides))); ?>">
                <div class="portfolio-content">
                    <?php if ($slide['label']): ?>
                        <p class="portfolio-label"><?= esc_html($slide['label']); ?></p>
                    <?php endif; ?>
                    <?php if ($slide['title']): ?>
                        <h2 class="portfolio-title"><?= wp_kses($slide['title'], array('br' => array())); ?>
                        </h2>
                    <?php endif; ?>
                    <?php if ($slide['cta']): ?>
                        <div class="btn-wrapper portfolio-action"><?= $slide['cta']; ?></div>
                    <?php endif; ?>
                </div>
                <p class="portfolio-pagination portfolio-pagination-<?= esc_attr($slide['color']); ?>"
                    aria-hidden="true">
                    <?= esc_html(sprintf('%02d / %02d', $index + 1, count($slides))); ?></p>
                <?php get_template_part('template-parts/components/section-next', null, array(
                    'anchor' => $slide_next_anchor,
                    'color' => $slide['color'],
                )); ?>
            </section>
        <?php endforeach; ?>
    </div>
</section>
