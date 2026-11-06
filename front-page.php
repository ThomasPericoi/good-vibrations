<?php
// Keep the native fallback when no static homepage is assigned.
if (get_option('show_on_front') !== 'page') {
    get_template_part('index');
    return;
}

$front_page_id = (int) get_option('page_on_front');
get_header();

if (post_password_required($front_page_id)) {
    echo get_the_password_form($front_page_id);
    get_footer();
    return;
}

if (!function_exists('get_field')) {
    get_footer();
    return;
}

get_template_part('template-parts/front-page-intro', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/components/editorial-section', null, array(
    'post_id' => $front_page_id,
    'prefix' => 'agency_1',
    'anchor' => 'agency-1',
    'class' => 'front-page-agency-1',
));

get_template_part('template-parts/components/editorial-section', null, array(
    'post_id' => $front_page_id,
    'prefix' => 'agency_2',
    'anchor' => 'agency-2',
    'class' => 'front-page-agency-2',
));


get_template_part('template-parts/front-page-conception', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/front-page-steps', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/front-page-stares', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/front-page-portfolio', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/front-page-download', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/components/editorial-section', null, array(
    'post_id' => $front_page_id,
    'prefix' => 'contact',
    'anchor' => 'contact',
    'class' => 'front-page-contact',
));

get_footer();
