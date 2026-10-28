<?php get_header(); ?>

<?php if (have_posts()):
    while (have_posts()):
        the_post(); ?>

                <?php
                $post_id = get_the_ID();
                $content = trim(get_the_content());
                $page_eyebrow = function_exists('get_field') ? get_field('page_eyebrow', $post_id) : '';
                ?>

        <!-- Page header -->
        <header id="page-header-<?= esc_attr($post_id); ?>"
            class="page-header<?= $content ? ' page-header-has-content' : ''; ?>">
            <div class="container container-sm">
                        <?php gv_render_breadcrumbs(); ?>
                        <?php if ($page_eyebrow): ?>
                    <p class="eyebrow page-eyebrow"><?= esc_html($page_eyebrow); ?></p>
                        <?php endif; ?>
                <h1><?= esc_html(get_the_title()); ?></h1>
                        <?php if (has_post_thumbnail() && !post_password_required()): ?>
                    <figure class="page-media">
                                <?php the_post_thumbnail('full'); ?>
                    </figure>
                        <?php endif; ?>
            </div>
        </header>

                <?php if ($content): ?>
            <!-- Content -->
            <section id="content-<?= esc_attr($post_id); ?>" class="page-content">
                <div class="container container-sm formatted">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages(array(
                                'before' => '<nav class="page-links" aria-label="' . esc_attr__('Navigation de la page', 'good-vibrations') . '">',
                                'after' => '</nav>',
                            ));
                            ?>
                </div>
            </section>
                <?php endif; ?>

        <?php endwhile; endif; ?>

<?php get_footer(); ?>
