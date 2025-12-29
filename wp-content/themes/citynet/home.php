<?php get_header(); ?>

<div class="container px-3 py-4">
    <?php citynet_get_site_template( 'page/single/title' ); ?>

    <div class="row gy-4">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <?php
            $category_filter = new CN_Post_Filter( 'taxonomy', 'category' );
            $filters = array( $category_filter );
            $filters = (array) apply_filters( 'CN_Post_Filters', $filters );
            citynet_get_site_template( 'global/filters', array(
                'filters' => $filters
            ) ); ?>
        </div>
        <div class="col-12 col-md-6 col-lg-8 col-xl-9">
            <?php
            $posts = citynet_get_posts();
            
            if ( $posts['items'] ) {
                $row_cols = citynet_get_loop_row_cols( true );
                $css_class = array_merge( array( 'row', 'gy-4' ), $row_cols ); ?>

                <div id="cards-wrapper" class="<?php echo esc_attr( implode( ' ', array_unique( $css_class ) ) ); ?>">
                    <?php
                    $args = array(
                        'title-tag' => 'h2',
                        'is-rtl'    => is_rtl()
                    );
                    $args = apply_filters( 'citynet_card_template_args', $args );
                    $posts['query']['template-args'] = $args;
                    
                    foreach ( $posts['items'] as $post ) {
                        setup_postdata( $post );
                        citynet_get_site_template( 'post/card', $args, '<div class="col">', '</div>' );
                    }
                    wp_reset_postdata();
                    
                    citynet_get_site_template( 'post/card/placeholder' ); ?>
                </div>

                <?php
                citynet_get_site_template( 'global/load-more', $posts );
                
            } else {
                citynet_get_site_template( 'global/no-items' );
            } ?>
        </div>
    </div>

    <?php
    $page_id = get_option( 'page_for_posts' );
    $sections_list = get_field( 'sections-list', $page_id );
    if ( $sections_list ) {
        foreach ( $sections_list as $section ) {
            if ( ! is_array( $section ) || ! isset( $section['acf_fc_layout'] ) || ! is_string( $section['acf_fc_layout'] ) ) continue;

            $template = $section['acf_fc_layout'];
            citynet_get_site_template( "blog/$template", array(
                'section' => $section
            ) );
        }
    } ?>
</div>

<?php get_footer(); ?>