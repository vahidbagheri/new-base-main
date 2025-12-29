<?php
/**
 * Template for displaying Category pages in CityNet Theme
 */

get_header();

$term = get_queried_object();
$term_id = $term->term_id ?? 0;
$title = $term->name ?? '';
$device = citynet_get_device();
?>

<div class="container px-3 py-4">
    <div class="row gy-4">

        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <?php
            $category_filter = new CN_Post_Filter( 'taxonomy', 'category' );
            $filters = array( $category_filter );
            $filters = (array) apply_filters( 'CN_Post_Filters', $filters );

            citynet_get_site_template( 'global/filters', [
                'filters' => $filters
            ] );
            ?>
        </div>

        <div class="col-12 col-md-6 col-lg-8 col-xl-9">
            <?php
            $is_ajax_filter = !empty($_GET['filters']) || (defined('DOING_AJAX') && DOING_AJAX);

            $args = [];
            if ( !$is_ajax_filter && $term_id ) {
                $args['category__in'] = [$term_id];
            }

            $posts = citynet_get_posts( $args );

            if ( $posts['items'] ) :
                $row_cols = citynet_get_loop_row_cols( true );
                $css_class = array_merge( ['row', 'gy-4'], $row_cols );
            ?>
                <div id="cards-wrapper" class="<?php echo esc_attr( implode( ' ', array_unique( $css_class ) ) ); ?>">
                    <?php
                    $args = [
                        'title-tag' => 'h2',
                        'is-rtl'    => is_rtl()
                    ];
                    $args = apply_filters( 'citynet_card_template_args', $args );
                    $posts['query']['template-args'] = $args;

                    foreach ( $posts['items'] as $post ) {
                        setup_postdata( $post );
                        citynet_get_site_template( 'post/card', $args, '<div class="col">', '</div>' );
                    }
                    wp_reset_postdata();

                    citynet_get_site_template( 'post/card/placeholder' );
                    ?>
                </div>

                <?php citynet_get_site_template( 'global/load-more', $posts ); ?>
            <?php else : ?>
                <?php citynet_get_site_template( 'global/no-items' ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
