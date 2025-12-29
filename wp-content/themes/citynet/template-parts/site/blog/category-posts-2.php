<?php
if ( ! is_array( $args ) || ! isset( $args['section'] ) ) return;

$section = $args['section'];
if ( ! is_array( $section ) || ! isset( $section['term'] ) ) return;

$term = $section['term'];
if ( ! $term instanceof WP_Term || ! $term->count ) return;

$query = new WP_Query( array(
    'cat'            => $term->term_id,
    'posts_per_page' => 4, 
    'orderby'        => 'date',
    'order'          => 'DESC'
) );

if ( ! $query->have_posts() ) return;

$posts_list = $query->get_posts();
?>

<section class="tours-section my-5">

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

    <div class="row g-4">
        <?php foreach ( $posts_list as $post ) : setup_postdata( $post ); ?>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
  
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="thumb-fixed d-block thumb-fixed-267-180 overflow-hidden">
                        <?php the_post_thumbnail(
                            'medium',
                            array(
                                'class'    => 'w-100 h-100 object-fit-cover',
                                'loading'  => 'lazy',
                                'alt'      => esc_attr( get_the_title() )
                            )
                        ); ?>
                    </a>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <h6 class="fw-bold text-center mb-4">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="text-dark text-decoration-none">
                                <?php the_title(); ?>
                            </a>
                        </h6>
                        <div class="text-center mb-2 mt-auto">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-secondary  btn-md px-4">
                                <?php echo esc_html__( 'More items', 'citynet' ) ;  ?>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>

</section>
