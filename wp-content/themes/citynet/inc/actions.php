<?php
add_action( 'init', function() {
    // Disable the emoji's
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', function( $plugins ) {
        return is_array( $plugins )? array_diff( $plugins, ['wpemoji'] ) : [];
    } );

    add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
        if ( $relation_type == 'dns-prefetch' ) :
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, [ $emoji_svg_url ] );
        endif;
        return $urls;
    }, 10, 2 );
    // End of disable the emoji's
    
    // Wordpress not allow to enqueue rtl.css automatically - because it included just for screen media
    remove_action( 'wp_head', 'locale_stylesheet' );
} );

add_action( 'after_setup_theme', function() {
    load_theme_textdomain( 'citynet', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    if ( current_user_can( 'subscriber' ) ) show_admin_bar( false );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( [
        'header' => esc_html__( 'Header', 'citynet' )
    ] );
} );

add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();
    $theme_path = get_template_directory();

    // Register your stylesheets here
    $style_deps = array();

    $bootstrap = is_rtl()? 'bootstrap.rtl.min.css' : 'bootstrap.min.css';
    wp_register_style( 'bootstrap', "$theme_uri/assets/css/$bootstrap", array(), null );
    $style_deps[] = 'bootstrap';

    wp_register_style( 'citynet', "$theme_uri/style.css", $style_deps, filemtime( "$theme_path/style.css" ) );
    if ( is_rtl() ) wp_register_style( 'citynet-rtl', "$theme_uri/rtl.css", array(), filemtime( "$theme_path/rtl.css" ) );

    // Register your JS files here
    wp_register_script( 'bootstrap', "$theme_uri/assets/js/bootstrap.bundle.min.js", array( 'jquery' ), null, true );
    wp_enqueue_script( 'citynet-general', "$theme_uri/assets/js/theme/site/general.js", array( 'bootstrap' ), filemtime( "$theme_path/assets/js/theme/site/general.js" ), true );

    wp_localize_script( 'citynet-general', 'themeAjax', array(
        'url'     => esc_url( admin_url( 'admin-ajax.php' ) ),
        'waiting' => false
    ) );
} );

add_action( 'wp_print_styles', function() {
    $extra_styles = array( 'wp-block-library', 'wpml-menu-item-0' );
	$extra_styles = apply_filters( 'citynet_dequeue_extra_styles', $extra_styles );
	if ( ! $extra_styles ) return;

    foreach ( $extra_styles as $handle )
        wp_dequeue_style( $handle );
}, 100 );

// Insert styles and scripts in admin panel
add_action( 'admin_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();
    $theme_path = get_template_directory();

    wp_enqueue_style( 'citynet-admin', "$theme_uri/assets/css/admin.css", [], filemtime( "$theme_path/assets/css/admin.css" ) );
} );

// Adds a link as shortcut to archive of post types
add_action( 'admin_menu', function() {
	global $submenu;

    // Add for blog
    $link = array(
        esc_html__( 'View archive', 'citynet' ),
        'manage_options',
        get_post_type_archive_link( 'post' )
    );
    $submenu['edit.php'][] = $link;

    // Add for custom post types
	$args = array(
        'has_archive' => true,
        '_builtin'    => false
    );
	$post_types = get_post_types( $args, 'names' );
    if ( ! $post_types ) return;

    foreach ( $post_types as $post_type ) {
        $key = "edit.php?post_type={$post_type}";
        $link[2] = get_post_type_archive_link( $post_type );
        $submenu[ $key ][] = $link;
    }
} );

add_action( 'pre_get_posts', function( $query ) {

    if ( defined('DOING_AJAX') && DOING_AJAX ) {
        $query->set( 'cat', '' );
        $query->set( 'category__in', '' );
    }
});
