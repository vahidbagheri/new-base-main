<?php
if ( ! is_array( $args ) || ! isset( $args['section'] ) ) return;

$section = $args['section'];
if ( ! is_array( $section ) || ! isset( $section['term'] ) ) return;

$term = $section['term'];
if ( ! $term instanceof WP_Term || ! $term->count ) return;

$query = new WP_Query( array(
    'cat'            => $term->term_id,
    'posts_per_page' => 3
) );

if ( ! $query->have_posts() ) return;

$posts_list = $query->get_posts();
?>

<section class="travel-section  my-5">

    <div class="section-title d-flex align-items-center justify-content-center mb-4">
        <h2 class="fw-bold mx-3 d-flex align-items-center position-relative">
            <?php
            $section_title = ( isset( $section['title'] ) && $section['title'] )? $section['title'] : $term->name;
            echo esc_html( $section_title ); ?>
        </h2>
    </div>


    <?php if ( ! empty( $section['description'] ) ) : ?>
        <p class="text-center text-muted mb-4">
            <?php echo esc_html( $section['description'] ); ?>
        </p>
    <?php endif; ?>

    <div class="rounded-3 p-4 bg-white shadow-sm">
        <?php foreach ( $posts_list as $post ) : setup_postdata( $post ); ?>
            <div class="d-flex flex-column flex-lg-row align-items-start mb-4 pb-3">
                
                <div class="thumb-fixed-218-170  overflow-hidden rounded-3 me-lg-3 mb-3 mb-lg-0">
                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                        <?php the_post_thumbnail(
                            'medium',
                            array(
                                'class'    => 'w-100 h-100 rounded-3 object-fit-cover',
                                'itemprop' => 'image',
                                'loading'  => 'lazy',
                                'alt'      => esc_attr( get_the_title() )
                            )
                        ); ?>
                    </a>
                </div>

                <div class="px-lg-3">
                    <h5 class="fw-bold mb-3">
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="text-dark text-decoration-none">
                            <?php the_title(); ?>
                        </a>
                    </h5>
                    <p class="text-muted lh-lg  mb-0">
                        <?php
                            $words_count = citynet_get_device() === 'mobile' ? 35 : 80;
                            echo wp_trim_words( get_the_content(), $words_count, '...' );
                        ?>
                    </p>
                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>

        <div class="text-end mt-4">
            <a href="<?php echo esc_url( get_category_link( $term ) ); ?>" class="btn btn-secondary px-4">
               <?php echo esc_html__( 'More items', 'citynet' ) ;  ?>
            </a>
        </div>
    </div>

</section>
