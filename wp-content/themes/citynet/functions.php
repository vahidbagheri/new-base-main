<?php
/**
 * Citynet functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Citynet
 */

require_once 'inc/classes/post-filter.php';
require_once 'inc/classes/location.php';
require_once 'inc/classes/hotel.php';
require_once 'inc/actions.php';
require_once 'inc/filters.php';
require_once 'inc/ajax-callbacks.php';
require_once 'inc/blog-function.php';

/**
 * Retrieves current device type based on 'wp_is_mobile()' result.
 *
 * @since 2.0.0
 * 
 * @return string One of 'mobile' or 'desktop' values.
 */
function citynet_get_device() {
    return wp_is_mobile()? 'mobile' : 'desktop';
}

/**
 * Retrieves template directory URI for the active child theme.
 *
 * @since 2.0.0
 *
 * @return string URI to active child theme's template directory.
 */
function citynet_get_child_template_directory_uri() {
    return sprintf( '%s-child', get_template_directory_uri() );
}

/**
 * Retrieves template directory path for the active child theme.
 *
 * @since 2.0.0
 *
 * @return string Path to active child theme's template directory.
 */
function citynet_get_child_template_directory() {
    return sprintf( '%s-child', get_template_directory() );
}

/**
 * Loads a site template part into a template based on 'get_template_part' function
 *
 * @since 2.0.0
 * 
 * @param string[]|string $slugs         The array or one of slug names for the generic site templates.
 * @param array           $args          Optional. Additional arguments passed to the site templates.
 * @param string          $wrapper_start Optional. The HTML content as start of wrapper. It must be scaped at the source. Default is empty string.
 * @param string          $wrapper_end   Optional. The HTML content as end of wrapper. It must be scaped at the source. Default is empty string.
 *
 * @return void
 */
function citynet_get_site_template( $slugs, $args = array(), $wrapper_start = '', $wrapper_end = '' ) {
    if ( ! $slugs ) return;
    if ( is_string( $slugs ) ) $slugs = array( $slugs );
    echo $wrapper_start;
    foreach ( $slugs as $slug ) get_template_part( "template-parts/site/$slug", null, $args );
    echo $wrapper_end;
}

/**
 * Determines whether WPML plugin is active.
 *
 * @since 2.0.0
 *
 * @return bool True, if in the active plugins list. False, not in the list.
 */
function citynet_is_wpml_active() {
    return is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' );
}

/**
 * Shows a Bootstrap alert message.
 *
 * @since 2.0.0
 * 
 * @param string $content The alert's HTML content for display. It must be scaped in the source.
 * @param string $type Optional. One of the alert type from Bootstrap alert types, default is 'info'.
 * @param array  $classes Optional. Extra classes for alert element.
 *
 * @return void
 */
function citynet_alert( $content, $type = 'info', $classes = array() ) {
    $args = array(
        'content' => $content,
        'type'    => $type,
        'classes' => $classes
    );
    citynet_get_site_template( 'global/alert', $args );
}

/**
 * Calculates estimate read time for a content in minutes.
 *
 * @since 2.0.0
 * 
 * @param string $content The HTML or not HTML content.
 *
 * @return int Calculated estimated read time. 
 */
function citynet_estimate_read_time( $content ) {
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes = ( $word_count > 300 )? intdiv( $word_count, 300 ) : 1;
    return $minutes;
}

if ( ! function_exists( 'citynet_is_fa' ) ) {
    /**
     * Checks if site viewing in Persian language or not.
     *
     * @since 1.0.0
     *
     * @return bool Returns true if is in Persian language, false otherwise. 
     */
    function citynet_is_fa() {
        return ( get_locale() === 'fa_IR' );
    }
}

/**
 * Converts english number to persian number or reverse.
 * 
 * @since 1.0.0
 *
 * @param mixed $num Desired number to convert.
 * @param string $mode Optional. If is 'eng' converts $num to English mode, else converts to Persian.
 * @param string $sp Float number seprator.
 *
 * @return string Converted numeric text.
 */
function citynet_convert_number( $num, $mode = 'eng', $sp = '٫' ) {
    $eng = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.' );
    $per = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $sp );

    return ( $mode === 'eng' )? str_replace( $per, $eng, $num ) : str_replace( $eng, $per, $num );
}

/**
 * Returns image's size-name dimensions (width and height).
 * 
 * @since 2.0.0
 *
 * @param string $size_name Name of image size.
 * @param bool $array_mode Optional. Specifies return value mode. If is false returns an string of dimensions and if is true return an array of it.
 *
 * @return string|int[]|false|null If image size exists return dimensions in order to $array_mode param else returns false.
 *                                 If not found one of width or height returns null.
 */
function citynet_get_image_size_dimensions( $size_name, $array_mode = false ) {
    $image_sizes = get_intermediate_image_sizes();
    if ( ! in_array( $size_name, $image_sizes ) ) return false;

    $width  = get_option( "{$size_name}_size_w" );
    if ( ! $width ) return null;

    $height = get_option( "{$size_name}_size_h" );
    if ( ! $height ) return null;

    if ( ! $array_mode ) return sprintf( '%dx%dpx', $width, $height );

    $dimensions = array(
        'width'  => (int) $width,
        'height' => (int) $height
    );
    return $dimensions;
}

/**
 * Displays breadcrumbs with Bootstrap 5 style and SEO.
 * 
 * @since 2.0.0
 *
 * @return void
 */
function citynet_breadcrumbs() {
    global $post;

    printf(
        '<nav aria-label="%s">
            <ol class="breadcrumb mb-4" itemscope itemtype="https://schema.org/BreadcrumbList">',
        esc_html__( 'Breadcrumb', 'citynet' )
    );

    $title = get_the_title( get_option( 'page_on_front' ) );
    $position = 1;
    $url = home_url();
    citynet_display_breadcrumb_item( $title, $position, $url );
    $position++;

    if ( is_singular() ) {
        $post_type = get_post_type();

        // If post type has archive
        if ( $post_type !=='page' ) {
            $pt_obj = get_post_type_object( $post_type );
            if ( ! empty( $pt_obj->has_archive ) ) {
                $url = get_post_type_archive_link( $post_type );
                citynet_display_breadcrumb_item( $pt_obj->labels->name, $position, $url );
                $position++;
            }
        }

        // Parent pages
        if ( is_page() && $post->post_parent ) {
            $ancestors = array_reverse( get_post_ancestors( $post->ID ) );
            foreach ( $ancestors as $ancestor ) {
                $title = get_the_title( $ancestor );
                $url = get_permalink( $ancestor );
                citynet_display_breadcrumb_item( $title, $position, $url );
                $position++;
            }
        }

        // Current Item
        $title = get_the_title();
        citynet_display_breadcrumb_item( $title, $position );

    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();
        citynet_display_breadcrumb_item( $term->name, $position );
    } elseif ( is_home() ) {
        $title = get_the_title( get_option( 'page_for_posts' ) );
        citynet_display_breadcrumb_item( $title, $position );
    } elseif ( is_post_type_archive() ) {
        $post_type = get_post_type();
        $pt_obj = get_post_type_object( $post_type );
        citynet_display_breadcrumb_item( $pt_obj->labels->name, $position );
    } elseif ( is_date() ) {
        if ( is_year() ) {
            $title = get_the_date( 'Y' );
        } elseif ( is_month() ) {
            $title = get_the_date( 'F Y' );
        } elseif ( is_day() ) {
            $title = get_the_date( 'F j, Y' );
        }
        citynet_display_breadcrumb_item( $title, $position );
    } elseif ( is_search() ) {
        citynet_display_breadcrumb_item( get_search_query(), $position );
    } elseif ( is_404() ) {
        citynet_display_breadcrumb_item( '404', $position );
    }

    echo '</ol></nav>';
}

/**
 * Displays a breadcrumb item.
 * 
 * @since 2.0.0
 *
 * @param string $title Title of item.
 * @param int $position The value used as content for meta itemprop="position".
 * @param string $url Optional. Link of item. Default is empty string that means it is current item.
 *
 * @return void
 */
function citynet_display_breadcrumb_item( $title, $position, $url = '' ) {
    printf(
        '<li class="breadcrumb-item%s"%s itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            %s
            <span itemprop="name">%s</span></a>
            <meta itemprop="position" content="%d" />
        </li>',
        $url? '' : ' active',
        $url? '' : ' aria-current="page"',
        $url? '<a href="' . esc_url( $url ) . '" class="text-decoration-none" itemprop="item">' : '',
        esc_html( $title ),
        $position
    );
}

/**
 * Returns Bootstrap row cols counts foreach place.
 * 
 * @since 2.0.0
 *
 * @param bool   $sidebar     Optional. Specifies the loop wrapper has a sidebar in large displays or not. Default is false.
 * @param string $section_key Optional. Used in related filter hook. Default is empty string.
 *
 * @return array Array of Bootstrap row cols.
 */
function citynet_get_loop_row_cols( $sidebar = false, $section_key = '' ) {
    $row_cols = array();
    if ( $sidebar ) {
        $row_cols = array(
            'xs' => 'row-cols-1',
            'lg' => 'row-cols-lg-2',
            'xl' => 'row-cols-xl-3'
        );
    } else {
        $row_cols = array(
            'xs' => 'row-cols-1',
            'md' => 'row-cols-md-2',
            'lg' => 'row-cols-lg-3',
            'xl' => 'row-cols-xl-4'
        );
    }
    $row_cols = (array) apply_filters( 'citynet_loop_row_cols', $row_cols, $sidebar, $section_key );
    return $row_cols;
}

/**
 * Returns css classes for a post card.
 * 
 * @since 2.0.0
 *
 * @param \WP_Post|string $post Post object or post-type of post. If it is post-type means return classes for placeholder box. 
 *
 * @return array CSS classes for card.
 */
function citynet_get_card_css_classes( $post ) {
    if ( ! is_a( $post, 'WP_Post' ) && ! is_string( $post ) ) return array();

    $css_class = array(
        'base'      => 'card-box',
        'bg-color'  => 'bg-white',
        'rounded'   => 'rounded-4',
        'height'    => 'h-100',
        'display'   => 'd-flex',
        'direction' => 'flex-column',
        'overflow'  => 'overflow-hidden'
    );

    $is_placeholder = is_string( $post );
    if ( $is_placeholder ) $css_class['placeholder'] = 'card-placeholder';

    $css_class = (array) apply_filters( 'citynet_card_css_classes', $css_class, $is_placeholder, $post );
    return $css_class;
}

/**
 * Gets WP_Query args, runs the query and returns structured result.
 * 
 * @since 2.0.0
 *
 * @param array $args Optional. The args for query by WP_Query. Default args is for first page of posts (blog).
 *
 * @return array Structured query result.
 */
function citynet_get_posts( $args = array() ) {
    $defaults = array(
        'post_type'      => 'post',
        'posts_per_page' => (int) get_option( 'posts_per_page' ),
        'paged'          => 1
    );
    $args = wp_parse_args( $args, $defaults );
    $args = (array) apply_filters( 'citynet_get_posts_args', $args );

    $result = array(
        'query' => $args,
        'items' => array(),
        'more'  => 0
    );

    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) return $result;

    $result['items'] = $query->get_posts();

    if ( $args['paged'] < $query->max_num_pages ) {
        $result['more'] = ( $args['paged'] < ( $query->max_num_pages - 1 ) )? (int) $args['posts_per_page'] : $query->found_posts - intval( $args['paged'] * $args['posts_per_page'] );
    }

    return $result;
}

/**
 * Generates beauty print_r
 *
 * @since 1.0.0
 *
 * @return void
 */
function citynet_print_r( $var ) {
	echo '<pre dir="ltr" style="text-align: left">' . print_r( $var, true ) . '</pre>';
}