<?php get_header(); ?>

<!-- Page content -->
<section class="error-page">
    <div class="container container-sm">
        <p class="eyebrow">404</p>
        <h1><?= esc_html__('Page introuvable', 'good-vibrations'); ?></h1>
        <p><?= esc_html__('Cette page n’existe pas ou a été déplacée.', 'good-vibrations'); ?></p>
        <a class="btn btn-primary" href="<?= esc_url(home_url('/')); ?>"><?= esc_html__('Retour à l’accueil', 'good-vibrations'); ?></a>
    </div>
</section>

<?php get_footer(); ?>
