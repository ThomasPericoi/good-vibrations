<?php
/* INIT
--------------------------------------------------------------- */

// Set up the theme baseline: translations, supports, and menu locations.
function gv_setup_theme()
{
    load_theme_textdomain('good-vibrations', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
        'header-text' => array('site-title', 'site-description'),
    ));
    add_theme_support(
        'html5',
        array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            'navigation-widgets',
        )
    );
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-font-sizes');

    register_nav_menus(
        array(
            'header-menu' => __('Header', 'good-vibrations'),
            'header-submenu' => __('Header submenu', 'good-vibrations'),
            'footer-submenu' => __('Footer submenu', 'good-vibrations'),
        )
    );
}
add_action('after_setup_theme', 'gv_setup_theme');

// Remove WordPress emoji assets from the front-end and admin.
function gv_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'gv_disable_emojis');

// Keep comments and pingbacks closed everywhere.
function gv_disable_comments_status()
{
    return false;
}
add_filter('comments_open', 'gv_disable_comments_status', 20, 2);
add_filter('pings_open', 'gv_disable_comments_status', 20, 2);

// Hide existing comments from public queries.
function gv_disable_comments_hide_existing_comments($comments)
{
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'gv_disable_comments_hide_existing_comments', 10, 2);

// Remove the comments screen from the admin menu.
function gv_disable_comments_admin_menu()
{
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'gv_disable_comments_admin_menu');

// Redirect users away from the comments admin screen.
function gv_disable_comments_admin_menu_redirect()
{
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'gv_disable_comments_admin_menu_redirect');

// Remove the comments dashboard widget.
function gv_disable_comments_dashboard()
{
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'gv_disable_comments_dashboard');

// Remove the comments shortcut from the admin bar.
function gv_disable_comments_icon_admin_bar()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'gv_disable_comments_icon_admin_bar');

// Remove comment and trackback support from every post type.
function gv_disable_comments_post_types_support()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'gv_disable_comments_post_types_support');

// Hide the WordPress version generator tag.
function gv_remove_wordpress_version()
{
    return '';
}
add_filter('the_generator', 'gv_remove_wordpress_version');

// Replace detailed login errors with a generic message.
function gv_hide_wordpress_errors()
{
    return __('Une erreur est survenue !', 'good-vibrations');
}
add_filter('login_errors', 'gv_hide_wordpress_errors');

// Disable xmlrpc.php
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// Use the newest CSS file timestamp as the main stylesheet version.
function gv_get_stylesheet_version()
{
    static $version = null;

    if ($version !== null) {
        return $version;
    }

    $version = filemtime(get_stylesheet_directory() . '/style.css');
    $css_directory = new RecursiveDirectoryIterator(get_stylesheet_directory() . '/assets/css', FilesystemIterator::SKIP_DOTS);
    $css_files = new RecursiveIteratorIterator($css_directory);

    foreach ($css_files as $css_file) {
        if ($css_file->getExtension() === 'css') {
            $version = max($version, $css_file->getMTime());
        }
    }

    return $version;
}

// Version individual assets from their file timestamp.
function gv_get_asset_version($relative_path)
{
    $path = get_stylesheet_directory() . '/' . ltrim($relative_path, '/');

    if (file_exists($path)) {
        return filemtime($path);
    }

    return gv_get_stylesheet_version();
}

// Add initial document state classes before the CSS is fully applied.
function gv_print_document_state_script()
{
    ?>
    <script>
        document.documentElement.classList.add('js-enabled');
    </script>
    <?php
}
add_action('wp_head', 'gv_print_document_state_script', 1);

// Load the project font on the website.
function gv_enqueue_google_fonts()
{
    wp_enqueue_style('gv-google-fonts', 'https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'gv_enqueue_google_fonts', 5);

// Prepare Google Fonts connections only when the stylesheet is enqueued.
function gv_google_fonts_resource_hints($urls, $relation_type)
{
    if ($relation_type !== 'preconnect' || !wp_style_is('gv-google-fonts', 'enqueued')) {
        return $urls;
    }

    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array(
        'href' => 'https://fonts.gstatic.com',
        'crossorigin' => 'anonymous',
    );

    return $urls;
}
add_filter('wp_resource_hints', 'gv_google_fonts_resource_hints', 10, 2);

// Register and enqueue theme styles only when they are needed.
function gv_enqueue_theme_stylesheets()
{
    wp_register_style('reset', get_template_directory_uri() . '/assets/css/inc/reset.css', array(), gv_get_stylesheet_version(), 'all');
    wp_register_style('wp-core', get_template_directory_uri() . '/assets/css/inc/wp-core.css', array(), gv_get_stylesheet_version(), 'all');
    wp_register_style('style', get_stylesheet_uri(), array('reset', 'wp-core', 'gv-google-fonts'), gv_get_stylesheet_version(), 'all');
    wp_enqueue_style('reset');
    wp_enqueue_style('wp-core');
    wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'gv_enqueue_theme_stylesheets');

// Register and enqueue theme scripts only when they are needed.
function gv_enqueue_theme_scripts()
{
    wp_register_script('ascii-printer', get_template_directory_uri() . '/assets/js/ascii-printer.min.js', array(), gv_get_asset_version('assets/js/ascii-printer.min.js'), true);
    wp_register_script('script', get_template_directory_uri() . '/assets/js/main.js', array('ascii-printer'), gv_get_asset_version('assets/js/main.js'), true);
    wp_enqueue_script('ascii-printer');
    wp_enqueue_script('script');
}
add_action('wp_enqueue_scripts', 'gv_enqueue_theme_scripts');

// Keep pingback discovery and the comments feed out of the public head.
add_filter('feed_links_show_comments_feed', '__return_false');

function gv_remove_pingback_header($headers)
{
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'gv_remove_pingback_header');

// Disable the native blog without deleting existing articles.
function gv_disable_posts()
{
    $post_type = get_post_type_object('post');

    if (!$post_type) {
        return;
    }

    $post_type->public = false;
    $post_type->publicly_queryable = false;
    $post_type->exclude_from_search = true;
    $post_type->show_ui = false;
    $post_type->show_in_menu = false;
    $post_type->show_in_admin_bar = false;
    $post_type->show_in_nav_menus = false;
    $post_type->show_in_rest = false;

    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'gv_disable_posts', 20);

// Keep native blog queries empty, including explicitly requested post searches.
function gv_exclude_posts_from_queries($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    $post_type = $query->get('post_type');

    if ($post_type === 'post' || (!$post_type && ($query->is_home() || $query->is_singular('post') || $query->is_category() || $query->is_tag() || $query->is_author() || $query->is_date() || $query->is_feed()))) {
        $query->set('post__in', array(0));
    } elseif (is_array($post_type) && in_array('post', $post_type, true)) {
        $post_types = array_values(array_diff($post_type, array('post')));
        if ($post_types) {
            $query->set('post_type', $post_types);
        } else {
            $query->set('post__in', array(0));
        }
    }
}
add_action('pre_get_posts', 'gv_exclude_posts_from_queries');
