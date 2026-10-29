<?php get_header(); ?>

<?php
$post_id = get_queried_object_id();

if (is_archive()) :
    $title = get_the_archive_title();
elseif (is_search()) :
    $title = sprintf(__('Résultats de recherche pour « %s »', 'good-vibrations'), esc_html(get_search_query(false)));
else :
    $title = get_bloginfo('name');
endif;
?>

<!-- Archive header -->
<header id="archive-header-<?= esc_attr($post_id ?: 'content'); ?>" class="page-header">
    <div class="container container-sm">
        <h1><?= wp_kses_post($title); ?></h1>
        <?php if (is_archive() && get_the_archive_description()) : ?>
            <div class="description formatted">
                <?= wp_kses_post(get_the_archive_description()); ?>
            </div>
        <?php endif; ?>
    </div>
</header>

<!-- Loop -->
<section id="archive-loop-<?= esc_attr($post_id ?: 'content'); ?>" class="archive-loop">
    <div class="container container-lg">
        <?php if (have_posts()) : ?>
            <div class="grid grid-2">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/components/item', 'content', array(
                        'index' => $wp_query->current_post,
                    )); ?>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(array(
                'prev_text' => esc_html__('Page précédente', 'good-vibrations'),
                'next_text' => esc_html__('Page suivante', 'good-vibrations'),
            )); ?>
        <?php else : ?>
            <?php get_template_part('template-parts/components/content-none'); ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
