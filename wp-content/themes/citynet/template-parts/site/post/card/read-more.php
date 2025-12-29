<?php
$defaults = array(
    'is-rtl' => is_rtl()? 'true' : 'false'
);
$params = wp_parse_args( $args, $defaults ); ?>

<a href="<?php the_permalink(); ?>" class="read-more link-body-emphasis text-decoration-none text-end">
    <?php
    esc_html_e( 'Read post', 'citynet' );
    printf(
        '<i class="%s ms-1 align-middle"></i>',
        ( $params['is-rtl'] == 'true' )? 'icon-chevron-left' : 'icon-chevron-right'
    ); ?>
</a>