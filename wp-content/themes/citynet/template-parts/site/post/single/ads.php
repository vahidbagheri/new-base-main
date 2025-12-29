<?php
if (function_exists('have_rows') && have_rows('sidebar_banner', 'option')): ?>
    <aside class="sidebar-banners bg-light rounded-4 shadow-sm mt-4" role="complementary">
        <div class="row g-3">
            <?php while (have_rows('sidebar_banner', 'option')): the_row();
                $image = get_sub_field('banner_image');
                $link  = get_sub_field('banner_link');
                $alt   = get_sub_field('alt');

                $img_url = '';
                if (is_array($image)) {
                    $img_url = isset($image['url']) ? $image['url'] : '';
                } else {
                    $img_url = $image;
                }

                if ($img_url && $link): ?>
                    <section class="col-12">
                        <article class="card border-0 bg-transparent">
                            <a href="<?php echo esc_url($link); ?>" 
                               target="_blank" 
                               rel="noopener" 
                               class="d-flex justify-content-center text-decoration-none">
                                <figure class="m-0">
                                    <img src="<?php echo esc_url($img_url); ?>" 
                                         alt="<?php echo esc_attr($alt); ?>" 
                                         class="img-fluid object-fit-cover rounded-3 shadow-sm" 
                                         loading="lazy" 
                                         decoding="async">
                                </figure>
                            </a>
                        </article>
                    </section>
                <?php endif;
            endwhile; ?>
        </div>
    </aside>
<?php endif; ?>
