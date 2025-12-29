<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$latest  = citynet_get_latest_posts($post_id);

if ($latest->have_posts()):
?>
<aside class="sidebar-latest" role="complementary">
    <header class="sidebar-blog-title d-flex align-items-center justify-content-center">
        <p class="fw-bold my-1 d-flex align-items-center mt-3 mx-1"><?php echo esc_html__('Latest Posts', 'citynet'); ?></p>
    </header>

    <ul class="list-group list-group-flush">
        <?php while ($latest->have_posts()): $latest->the_post(); ?>
            <li class="list-group-item bg-transparent border-0 px-2 py-2">
                <article class="d-flex align-items-center justify-content-between">
                    <?php if (has_post_thumbnail()): ?>
                        <figure class="ms-2 flex-shrink-0 m-0">
                            <a href="<?php echo get_the_permalink(); ?>">
                                <?php the_post_thumbnail(
                                    array(70, 70),
                                    array(
                                        'class' => 'rounded-3 img-fluid',
                                        'alt'   => esc_attr(get_the_title())
                                    )
                                ); ?>
                            </a>
                        </figure>
                    <?php endif; ?>

                    <div class="flex-grow-1">
                        <a href="<?php echo get_the_permalink(); ?>" class="text-dark px-2 text-decoration-none fw-semibold small d-inline-block">
                            <?php echo mb_strimwidth(get_the_title(), 0, 25, '...', 'UTF-8'); ?>
                        </a>
                    </div>
                </article>
            </li>
        <?php endwhile; wp_reset_postdata(); ?>
    </ul>
</aside>
<?php endif; ?>
