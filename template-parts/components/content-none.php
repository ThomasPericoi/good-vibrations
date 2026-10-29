<div class="formatted">
    <p><?= esc_html__('Aucun contenu trouvé.', 'good-vibrations'); ?></p>
    <?php if (is_search()): ?>
        <?php get_search_form(); ?>
    <?php endif; ?>
</div>
