<?php
$post_id = $args['post_id'];
$title = get_field('steps_title', $post_id);
$background = get_field('steps_background', $post_id);
$steps = get_field('steps_steps', $post_id);
$primary_cta = gv_get_cta('steps_primary', $post_id);
$secondary_cta = gv_get_cta('steps_secondary', $post_id);
$next_anchor = sanitize_title(ltrim((string) get_field('steps_next_anchor', $post_id), '#'));

if (!$title && !$steps && !$primary_cta && !$secondary_cta) {
    return;
}
?>

<!-- Steps -->
<section id="steps" class="front-page-steps editorial-background-<?= esc_attr($background); ?> <?= $next_anchor ? 'has-section-next' : ''; ?>">
    <div class="steps-content">
        <?php if ($title): ?>
            <h2 class="steps-title"><?= wp_kses($title, array('br' => array())); ?></h2>
        <?php endif; ?>
        <?php if ($steps): ?>
            <ol class="steps-steps">
                <?php foreach ($steps as $step): ?>
                    <?php if ($step['steps_step_title'] || $step['steps_step_text']): ?>
                        <li>
                            <?php if ($step['steps_step_title']): ?>
                                <h3><?= esc_html($step['steps_step_title']); ?></h3>
                            <?php endif; ?>
                            <?php if ($step['steps_step_text']): ?>
                                <p><?= wp_kses($step['steps_step_text'], array('br' => array())); ?></p>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
        <?php if ($primary_cta || $secondary_cta): ?>
            <div class="btn-wrapper steps-actions">
                <?= $primary_cta; ?>
                <?= $secondary_cta; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php get_template_part('template-parts/components/section-next', null, array(
        'anchor' => $next_anchor,
        'color' => 'light',
    )); ?>
</section>
