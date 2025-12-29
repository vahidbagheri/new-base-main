<?php
$categories = get_the_category();
if ( ! $categories ) return; ?>

<div class="post-categories position-absolute bottom-0 start-0 d-flex flex-wrap p-2" itemprop="about" itemscope itemtype="https://schema.org/Thing">
    <?php
    foreach ( $categories as $category ) {
        printf(
            '<a class="post-category badge rounded-pill m-1 py-2 text-bg-primary fw-light fs-6 text-decoration-none" href="%s" rel="category" itemprop="url"><span itemprop="name">%s</span></a>',
            esc_url( get_category_link( $category->term_id ) ),
            esc_html( $category->name )
        );
    } ?>
</div>