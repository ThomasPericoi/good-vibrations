<?php
$post_id = $args['post_id'];
$title = get_field('stares_title', $post_id);
$members = get_field('stares_members', $post_id);
$next_anchor = sanitize_title(ltrim((string) get_field('stares_next_anchor', $post_id), '#'));

if (!$title && !$members) {
    return;
}
?>

<!-- Stares -->
<section id="stares" class="front-page-stares <?= $next_anchor ? 'has-section-next' : ''; ?>">
    <div class="stares-content">
        <?php if ($title): ?>
            <h2 class="stares-title"><?= wp_kses($title, array('br' => array())); ?></h2>
        <?php endif; ?>
        <?php if ($members): ?>
            <div class="stares-members">
                <?php foreach ($members as $member): ?>
                    <?php if ($member['stares_member_image'] || $member['stares_member_name'] || $member['stares_member_role'] || $member['stares_member_bio']): ?>
                        <div class="stares-member">
                            <?php if ($member['stares_member_image']): ?>
                                <?= wp_get_attachment_image($member['stares_member_image'], 'large', false, array(
                                    'class' => 'stares-portrait',
                                    'sizes' => '(min-width: 992px) 26vw, (min-width: 768px) 42vw, 86vw',
                                )); ?>
                            <?php endif; ?>
                            <?php if ($member['stares_member_name'] || $member['stares_member_role']): ?>
                                <h3 class="stares-name">
                                    <?= esc_html($member['stares_member_name']); ?>
                                    <?php if ($member['stares_member_name'] && $member['stares_member_role']): ?> / <?php endif; ?>
                                    <?php if ($member['stares_member_role']): ?>
                                        <span><?= esc_html($member['stares_member_role']); ?></span>
                                    <?php endif; ?>
                                </h3>
                            <?php endif; ?>
                            <?php if ($member['stares_member_bio']): ?>
                                <div class="stares-bio formatted"><?= wp_kses_post($member['stares_member_bio']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php get_template_part('template-parts/components/section-next', null, array(
        'anchor' => $next_anchor,
        'color' => 'dark',
    )); ?>
</section>
