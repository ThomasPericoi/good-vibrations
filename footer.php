</main>

<?php
$footer_contacts = function_exists('get_field') ? get_field('footer_contacts', 'options') : array();
$footer_contacts = is_array($footer_contacts) ? array_filter($footer_contacts, function ($contact) {
    return !empty($contact['footer_contact_email']) && is_email($contact['footer_contact_email']);
}) : array();
$footer_logo_id = function_exists('get_field') ? get_field('footer_logo', 'options') : 0;
$footer_logo = $footer_logo_id ? wp_get_attachment_image((int) $footer_logo_id, 'medium_large') : '';
$footer_has_custom_logo = !empty($footer_logo);
if (!$footer_has_custom_logo) {
    $footer_logo = wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'medium_large');
}
$footer_address = function_exists('get_field') ? get_field('footer_address', 'options') : '';
$footer_copyright = function_exists('get_field') ? get_field('footer_copyright', 'options') : '';
$footer_has_menu = has_nav_menu('footer-submenu');
$footer_has_identity = $footer_logo || $footer_address;
$footer_has_legal = $footer_copyright || $footer_has_menu;
$footer_has_content = $footer_contacts || $footer_has_identity || $footer_has_legal;
?>

<?php if ($footer_has_content): ?>
    <!-- Footer -->
    <footer id="footer">
        <div class="container container-lg">
            <?php if ($footer_contacts): ?>
                <!-- Main Footer -->
                <div id="main-footer">
                    <div class="footer-contacts">
                        <?php foreach ($footer_contacts as $contact): ?>
                            <div class="footer-contact">
                                <?php if (!empty($contact['footer_contact_label'])): ?>
                                    <p class="eyebrow footer-title">
                                        <?= esc_html($contact['footer_contact_label']); ?></p>
                                <?php endif; ?>
                                <a
                                    href="mailto:<?= esc_attr(sanitize_email($contact['footer_contact_email'])); ?>"><?= esc_html($contact['footer_contact_email']); ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($footer_has_identity || $footer_has_legal): ?>
                <!-- Sub Footer -->
                <div id="sub-footer">
                    <?php if ($footer_has_identity): ?>
                        <div class="footer-identity">
                            <?php if ($footer_logo): ?>
                                <a class="footer-logo<?= !$footer_has_custom_logo ? ' footer-logo-default' : ''; ?>"
                                    href="<?= esc_url(home_url('/')); ?>"
                                    aria-label="<?= esc_attr__('Retour à l’accueil', 'good-vibrations'); ?>"><?= $footer_logo; ?></a>
                            <?php endif; ?>
                            <?php if ($footer_address): ?>
                                <address class="footer-address"><?= nl2br(esc_html($footer_address)); ?></address>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($footer_has_legal): ?>
                        <div class="footer-legal">
                            <?php if ($footer_copyright): ?>
                                <p class="footer-copyright">&copy; <?= esc_html(wp_date('Y')); ?>
                                    <?= esc_html($footer_copyright); ?></p>
                            <?php endif; ?>
                            <?php if ($footer_has_menu): ?>
                                <nav
                                    aria-label="<?= esc_attr__('Navigation de pied de page', 'good-vibrations'); ?>">
                                    <?php wp_nav_menu(array(
                                        'theme_location' => 'footer-submenu',
                                        'menu_class' => 'menu menu-footer',
                                        'container' => false,
                                        'depth' => 1,
                                        'fallback_cb' => false,
                                    )); ?>
                                </nav>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>

</html>
