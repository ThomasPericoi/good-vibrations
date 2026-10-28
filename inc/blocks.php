<?php
/* BLOCKS
--------------------------------------------------------------- */

// Register every ACF block declared in the local blocks directory.
function gv_register_acf_blocks()
{
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    $blocks = ['button'];

    foreach ($blocks as $block) {
        $block_directory = __DIR__ . '/blocks/' . $block;

        if (file_exists($block_directory . '/block.json')) {
            register_block_type($block_directory);
        }
    }
}
add_action('init', 'gv_register_acf_blocks');

// Add the theme block category to the editor inserter.
function gv_register_block_category($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'good-vibrations',
                'title' => __('VIBÉO', 'good-vibrations'),
            ),
        )
    );
}
add_filter('block_categories_all', 'gv_register_block_category', 10, 2);
