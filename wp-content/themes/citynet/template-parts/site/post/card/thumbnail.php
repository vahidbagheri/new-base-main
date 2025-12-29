<a href="<?php the_permalink(); ?>" class="post-thumbnail" aria-hidden="true" tabindex="-1">
    <?php
    the_post_thumbnail(
        'medium',
        array(
            'class'    => 'w-100',
            'itemprop' => 'image',
            'loading'  => 'lazy',
            'alt'      => esc_attr( get_the_title() )
        )
    ); ?>
</a>