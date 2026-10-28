<?php
$index = isset($args['index']) ? absint($args['index']) : 0;
?>

<article id="post-<?= esc_attr(get_the_ID()); ?>" <?php post_class('item-content'); ?> data-index="<?= esc_attr($index); ?>">
    <?php if (has_post_thumbnail() && !post_password_required()) : ?>
        <a href="<?= esc_url(get_permalink()); ?>" class="media" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail('large'); ?>
        </a>
    <?php endif; ?>

    <div class="content">
        <h2 class="title"><a href="<?= esc_url(get_permalink()); ?>"><?= esc_html(get_the_title()); ?></a></h2>
        <?php if (get_the_excerpt()) : ?>
            <div class="description"><?= wp_kses_post(get_the_excerpt()); ?></div>
        <?php endif; ?>
    </div>
</article>
