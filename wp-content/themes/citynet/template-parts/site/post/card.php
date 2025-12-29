<?php
/** @var WP_Post $post */

$defaults = array(
    'title-tag' => 'h3',
    'is-rtl'    => is_rtl()
);
$params = wp_parse_args( $args, $defaults );

$templates = array(
    'thumbnail' => array( 'post/card/thumbnail', 'post/card/copy-link', 'post/card/categories' ),
    'title'     => array( 'post/card/title' ),
    'body'      => array(),
    'footer'    => array( 'post/card/read-time', 'post/card/read-more' ),
);
$templates = (array) apply_filters( 'citynet_post_card_templates', $templates, $post );
if ( ! $templates ) return;

$css_class = citynet_get_card_css_classes( $post ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?> itemscope itemtype="https://schema.org/BlogPosting">
    <?php
    if ( isset( $templates['thumbnail'] ) || isset( $templates['title'] ) ) {
        echo '<header class="post-header">';

        if ( isset( $templates['thumbnail'] ) ) {
            citynet_get_site_template( $templates['thumbnail'], $params, '<div class="position-relative">', '</div>' );
        }
        
        if ( isset( $templates['title'] ) ) {
            citynet_get_site_template( $templates['title'], $params, '<div class="position-relative">', '</div>' );
        }

        echo '</header>';
    }
    
    if ( isset( $templates['body'] ) ) {
        citynet_get_site_template( $templates['body'], $params, '<div class="position-relative">', '</div>' );
    }
    
    if ( isset( $templates['footer'] ) ) {
        echo '<footer class="post-footer mt-auto">';
        citynet_get_site_template(
            $templates['footer'],
            $params,
            '<div class="d-flex justify-content-between px-3 py-2 small text-capitalize">',
            '</div>'
        );
        echo '</footer>';
    } ?>
</article>