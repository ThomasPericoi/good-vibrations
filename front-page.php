<?php
// Keep the native fallback when no static homepage is assigned.
if (get_option('show_on_front') !== 'page') {
    get_template_part('index');
    return;
}

$front_page_id = (int) get_option('page_on_front');
get_header();

get_template_part('template-parts/front-page-intro', null, array(
    'post_id' => $front_page_id,
));

get_template_part('template-parts/front-page-agency', null, array(
    'post_id' => $front_page_id,
));

get_footer();
