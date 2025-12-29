<?php
if ( ! is_array( $args ) || ! isset( $args['section'] ) ) return;

$section = $args['section'];
if ( ! is_array( $section ) || ! isset( $section['term'] ) ) return;

$term = $section['term'];
if ( ! $term instanceof WP_Term || ! $term->count ) return;

$query = new WP_Query( array(
    'cat'            => $term->term_id,
    'posts_per_page' => 8, 
    'orderby'        => 'date',
    'order'          => 'DESC'
) );

if ( ! $query->have_posts() ) return;

$posts_list = $query->get_posts();
?>

<section class="destinations-section my-5 container">

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
            <div class="col-12 col-md-4 col-lg-3">
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="thumb-fixed rounded-3 d-block  position-relative thumb-fixed-272-279  overflow-hidden">

                    <?php the_post_thumbnail(
                        'medium',
                        array(
                            'class'    => 'w-100 h-100 object-fit-cover',
                            'loading'  => 'lazy',
                            'alt'      => esc_attr( get_the_title() )
                        )
                    ); ?>

                     <div class="p-3 position-absolute text-center start-0 w-100 bottom-0 gradient-overlay">

                        <span class="post-title fs-6 lh-lg d-block py-2 rounded-3 text-white" itemprop="headline">
                            <?php the_title(); ?>
                        </span>

                    </div>

                </a>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>

</section>
