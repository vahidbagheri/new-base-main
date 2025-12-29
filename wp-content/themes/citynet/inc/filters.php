<?php
// Add custom classes to body
add_filter( 'body_class', function( array $classes ) {
    $classes[] = sprintf( 'cnwp-device-%s', citynet_get_device() );
    $classes[] = 'bg-body-tertiary';
    return $classes;
} );

// Adds handle name to all initialized scripts
add_filter( 'script_loader_tag', function( string $tag, string $handle ) {
	return str_replace( '<script', sprintf( "<script handle='%s'", esc_attr( $handle ) ), $tag );
}, 10, 2 );

// Adds handle name to all initialized styles
add_filter( 'style_loader_tag', function( string $tag, string $handle ) {
	return str_replace( '<link', sprintf( "<link handle='%s'", esc_attr( $handle ) ), $tag );
}, 10, 2 );

// Hides wordpress version from front side
add_filter( 'the_generator', '__return_empty_string' );

// Prevent access to wordpress extra APIs for guests
add_filter( 'rest_endpoints', function( array $endpoints ) {
    if ( is_user_logged_in() ) return $endpoints;

    $extra_endpoints = array(
        '/wp/v2/users',
        '/wp/v2/users/(?P<id>[\d]+)',
        '/wp/v2/posts',
        '/wp/v2/posts/(?P<id>[\d]+)',
        '/wp/v2/pages',
        '/wp/v2/pages/(?P<id>[\d]+)'
    );

    foreach ( $extra_endpoints as $item )
        if ( isset( $endpoints[ $item ] ) ) unset( $endpoints[ $item ] );

	return $endpoints;
} );

// Shows same message for wrong submitted username or password
add_filter( 'login_errors', function() {
	return esc_html__( 'Username or password is wrong!', 'citynet' );
} );

// Add bootstrap classes to nav menu items
add_filter( 'nav_menu_css_class', function( $classes, $menu_item, $args ) {
    if ( property_exists( $args, 'item_class' ) ) $classes[] = $args->item_class;
    return $classes;
}, 10, 3 );

// Add bootstrap classes to nav menu links
add_filter( 'nav_menu_link_attributes', function( $atts, $menu_item, $args ) {
    $classes = [];
    if ( property_exists( $args, 'link_class' ) ) $classes[] = $args->link_class;
    if ( citynet_get_device() === 'mobile' && in_array( 'menu-item-has-children', $menu_item->classes ) ) $classes[] = 'me-5';
    if ( $classes ) $atts['class'] = implode( ' ', $classes );
    return $atts;
}, 10, 3 );

// Add optional icon to nav menu text
add_filter( 'wp_setup_nav_menu_item', function( $item ) {
    if ( is_admin() ) return $item;

    $icon = array_find( $item->classes, function( string $value ) {
        return str_starts_with( $value, 'item-icon-' );
    } );
    if ( ! $icon ) return $item;

    $icon = explode( '-', $icon, 2 )[1];
    $item->title = sprintf( '<i class="%s me-1 fs-6"></i>%s', esc_attr( $icon ), $item->title );
    return $item;
} );

// Translate ACF field texts in order to admin profile language
add_filter( 'acf/prepare_field', function( $field ) {
    $check_params = array( 'label', 'instructions', 'button_label' );
    foreach ( $check_params as $param ) {
        if ( ! isset( $field[ $param ] ) || ! $field[ $param ] ) continue;
        $field[ $param ] = esc_html__( $field[ $param ], 'citynet' );
    }
    if ( $field['type'] !== 'flexible_content' || !$field['layouts'] ) return $field;

    foreach ( $field['layouts'] as &$layout ) {
        $layout['label'] = esc_html__( $layout['label'], 'citynet' );
    }
    return $field;
} );

// Translate ACF options page texts in order to admin profile language
add_filter( 'acf/get_options_page', function( $page, $slug ) {
    if ( $slug === 'citynet-settings' ) return $page;

    $check_params = array( 'page_title', 'menu_title', 'update_button', 'updated_message', 'description' );
    foreach ( $check_params as $param ) {
        if ( ! isset( $page[ $param ] ) || ! $page[ $param ] ) continue;
        $page[ $param ] = esc_html__( $page[ $param ], 'citynet' );
    }
    return $page;
}, 10, 2 );

// Translate ACF field groups texts in order to admin profile language
add_filter( 'acf/load_field_groups', function( $field_groups ) {
    if ( ! $field_groups ) return $field_groups;

    $check_params = array( 'title', 'description' );
    foreach ( $field_groups as &$field_group ) {
        if ( isset( $field_group['location'][0][0] )
        && $field_group['location'][0][0]['param'] === 'options_page'
        && $field_group['location'][0][0]['value'] === 'citynet-settings' ) continue;

        foreach ( $check_params as $param ) {
            if ( ! isset( $field_group[ $param ] ) || ! $field_group[ $param ] ) continue;
            $field_group[ $param ] = esc_html__( $field_group[ $param ], 'citynet' );
        }
    }
    return $field_groups;
}, 999 );

// Translate Site title and description
add_filter( 'pre_option', function( $pre, $option ) {
    $check_options = array( 'blogname', 'blogdescription' );
    return in_array( $option, $check_options )? get_field( "theme-$option", 'options', true, true ) : $pre;
}, 10, 2 );

// Rebuild HTML of languages switcher menu in header
add_filter( 'wp_nav_menu_items', function( $items, $args ) {
    if ( $args->theme_location !== 'header' || !citynet_is_wpml_active() ) return $items;

    $menu_items = explode( '</li>', $items );
    $lang_items = preg_grep( '/<li.*class=".* wpml-ls-menu-item .*".*>.*/', $menu_items );
    if ( ! $lang_items ) return $items;

    $old_items = implode( '</li>', $lang_items ) . '</li>';
    $device = citynet_get_device();
    $submenu_toggle_icon = is_rtl()? 'icon-arrow-left-1' : 'icon-arrow-right-4';

    $new_items = sprintf(
        '<li class="menu-item menu-item-has-children wpml-ls-item nav-item">
            <a href="#" class="nav-link %s">
                %s<i class="icon-global me-1 fs-6"></i>%s
            </a>
            %s
            <ul class="sub-menu">%s</ul>
        </li>',
        ( $device === 'desktop' )? 'link-body-emphasis' : 'text-truncate me-5',
        is_rtl()? 'Languages' : '',
        is_rtl()? '' : 'Languages',
        ( $device === 'mobile' )? "<i class=\"sub-menu-toggle {$submenu_toggle_icon} float-end fs-5\" aria-hidden=\"true\"></i>" : '',
        $old_items
    );
    return str_replace( $old_items, $new_items, $items );
}, 10, 2 );

// Add arrow-bottom icon for mobile header menu items with children
add_filter( 'walker_nav_menu_start_el', function( $item_output, $menu_item, $depth, $args ) {
    $device = citynet_get_device();
    if ( $device === 'desktop' || $args->theme_location !== 'header' || !in_array( 'menu-item-has-children', $menu_item->classes ) ) return $item_output;
    $submenu_toggle_icon = is_rtl()? 'icon-arrow-left-1' : 'icon-arrow-right-4';
    $item_output .= "<i class=\"sub-menu-toggle {$submenu_toggle_icon} float-end fs-5\" aria-hidden=\"true\"></i>";
    return $item_output;
}, 10, 4 );

// Convert all dates to jalali date when language is Persian
add_filter( 'wp_date', function( string $date, string $format, int $timestamp, DateTimeZone $timezone ) {
    if ( get_locale() !== 'fa_IR' || ! function_exists( 'parsidate' ) ) return $date;

    $standard_date = date( 'Y/m/d H:i:s', $timestamp );
    $date_time = new DateTime( $standard_date, new DateTimeZone( 'GMT' ) );
    $date_time->setTimezone( $timezone );
    return parsidate( $format, $date_time->format( 'Y/m/d H:i:s' ) );
}, 10, 4 );

// Add description for thumbnail image UI in related meta box in admin area
add_filter( 'admin_post_thumbnail_html', function( $content ) {
    $dimensions = citynet_get_image_size_dimensions( 'medium' );
    if ( ! $dimensions ) return $content;

    $content = sprintf( '<p class="howto">%s</p>', $dimensions ) . $content;
    return $content;
} );

// Add dimensions instruction for default feature image UI in admin area
add_filter( 'acf/prepare_field/key=field_68aeb1e7e593f', function( $field ) {
    $dimensions = citynet_get_image_size_dimensions( 'medium' );
    if ( ! $dimensions ) return $field;

    $field['instructions'] = $dimensions;
    return $field;
} );

// If post dont has thumbnail image, returns default feature image id
add_filter( 'post_thumbnail_id', function( $thumbnail_id ) {
    return $thumbnail_id? $thumbnail_id : (int) get_field( 'theme-default-feature-image', 'option' );
} );