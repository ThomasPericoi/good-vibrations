<?php
$post_id = $args['post_id'] ?? get_the_ID();
if (!function_exists('get_field')) {
    return;
}

get_template_part('template-parts/components/editorial-section', null, array(
    'post_id' => $post_id,
    'prefix' => 'agency_1',
    'anchor' => 'agency-1',
    'class' => 'front-page-agency-1',
));

get_template_part('template-parts/components/editorial-section', null, array(
    'post_id' => $post_id,
    'prefix' => 'agency_2',
    'anchor' => 'agency-2',
    'class' => 'front-page-agency-2',
));
