<?php
/**
 * Button Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param bool $is_preview True during backend preview render.
 */

$button = gv_get_cta('block');
if (!$button && empty($is_preview)) {
    return;
}
?>

<!-- Block - Button -->
<div <?= get_block_wrapper_attributes(array('class' => 'button-block')); ?>>
    <?php if ($button) : ?>
        <?= $button; ?>
    <?php else : ?>
        <p><?= esc_html__('Choisissez un lien pour afficher le bouton.', 'good-vibrations'); ?></p>
    <?php endif; ?>
</div>
