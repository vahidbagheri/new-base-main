<?php
add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = citynet_get_child_template_directory_uri();
    $theme_path = citynet_get_child_template_directory();
    $style_deps = array( 'citynet' );

    wp_enqueue_style( 'citynet-child', get_stylesheet_uri(), $style_deps, filemtime( "$theme_path/style.css" ) );
    if ( is_rtl() ) wp_enqueue_style( 'citynet-child-rtl', get_locale_stylesheet_uri(), array( 'citynet-rtl' ), filemtime( "$theme_path/rtl.css" ) );

    wp_enqueue_script( 'citynet-child', "$theme_uri/assets/js/general.js", array( 'citynet-general' ), filemtime( "$theme_path/assets/js/general.js" ), true );
} );
