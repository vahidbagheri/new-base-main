<?php
if ( ! is_array( $args ) || ! isset( $args['section'] ) ) return;

$section = $args['section'];
if ( ! is_array( $section ) || ! isset( $section['term'] ) ) return;

$term = $section['term'];
if ( ! $term instanceof WP_Term || ! $term->count ) return;

$query = new WP_Query( array(
    'cat' => $term->term_id,
    'posts_per_page' => 4
) );
if ( ! $query->have_posts() ) return;

$posts_list = $query->get_posts(); ?>

<section class="blog-category-grid mt-5">
    
    <div class="section-title d-flex align-items-center justify-content-center mb-4">
        <h2 class="fw-bold mx-3 d-flex align-items-center position-relative">
            <?php
            $section_title = ( isset( $section['title'] ) && $section['title'] )? $section['title'] : $term->name;
            echo esc_html( $section_title ); ?>
        </h2>
    </div>


    <?php
    if ( isset( $section['description'] ) && $section['description'] )
        printf( '<span class="d-block text-secondary">%s</span>', esc_html( $section['description'] ) ); ?>

    <div class="row mt-3 g-2 row-cols-1 row-cols-lg-2">
        <div class="col">
            <?php
            $post = $posts_list[0];
            setup_postdata( $post ); ?>

            <a href="<?php echo esc_url( get_permalink() ); ?>" class="fw-bold link-light text-decoration-none" itemprop="url">
                <div class="post-wrapper h-100 position-relative rounded-3 overflow-hidden">
                    <?php
                    the_post_thumbnail(
                        'medium',
                        array(
                            'class'    => 'w-100 h-100 object-fit-cover',
                            'itemprop' => 'image',
                            'loading'  => 'lazy',
                            'alt'      => esc_attr( get_the_title() )
                        )
                    ); ?>

                    <span class="post-title fs-6 lh-lg position-absolute text-center start-0 w-100 bottom-0 p-3 bg-dark bg-opacity-75" itemprop="headline">
                        <?php the_title(); ?>
                    </span>
                </div>
            </a>
        </div>

        <?php if ( isset( $posts_list[1] ) ) { ?>
            <div class="col">
                <?php
                $post = $posts_list[1];
                setup_postdata( $post ); ?>

                <a href="<?php echo esc_url( get_permalink() ); ?>" class="fw-bold link-light text-decoration-none" itemprop="url">
                    <div class="post-wrapper small-item position-relative rounded-3 overflow-hidden">
                        <?php
                        the_post_thumbnail(
                            'medium',
                            array(
                                'class'    => 'w-100 h-100 object-fit-cover',
                                'itemprop' => 'image',
                                'loading'  => 'lazy',
                                'alt'      => esc_attr( get_the_title() )
                            )
                        ); ?>

                        <span class="post-title fs-6 lh-lg position-absolute text-center start-0 w-100 bottom-0 p-3 bg-dark bg-opacity-75" itemprop="headline">
                            <?php the_title(); ?>
                        </span>
                    </div>
                </a>
                
                <?php if ( isset( $posts_list[2] ) ) { ?>
                    <div class="row gx-2 mt-2 row-cols-2">
                        <div class="col">
                            <?php
                            $post = $posts_list[2];
                            setup_postdata( $post ); ?>

                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="fw-bold link-light text-decoration-none" itemprop="url">
                                <div class="post-wrapper small-item position-relative rounded-3 overflow-hidden">
                                    <?php
                                    the_post_thumbnail(
                                        'medium',
                                        array(
                                            'class'    => 'w-100 h-100 object-fit-cover',
                                            'itemprop' => 'image',
                                            'loading'  => 'lazy',
                                            'alt'      => esc_attr( get_the_title() )
                                        )
                                    ); ?>

                                    <span class="post-title fs-6 lh-lg position-absolute text-center start-0 w-100 bottom-0 p-3 bg-dark bg-opacity-75" itemprop="headline">
                                        <?php the_title(); ?>
                                    </span>
                                </div>
                            </a>
                        </div>

                        <?php if ( isset( $posts_list[3] ) ) { ?>
                            <div class="col">
                                <?php
                                $post = $posts_list[3];
                                setup_postdata( $post ); ?>

                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="fw-bold link-light text-decoration-none" itemprop="url">
                                    <div class="post-wrapper small-item position-relative rounded-3 overflow-hidden">
                                        <?php
                                        the_post_thumbnail(
                                            'medium',
                                            array(
                                                'class'    => 'w-100 h-100 object-fit-cover',
                                                'itemprop' => 'image',
                                                'loading'  => 'lazy',
                                                'alt'      => esc_attr( get_the_title() )
                                            )
                                        ); ?>

                                        <span class="post-title fs-6 lh-lg position-absolute text-center start-0 w-100 bottom-0 p-3 bg-dark bg-opacity-75" itemprop="headline">
                                            <?php the_title(); ?>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>