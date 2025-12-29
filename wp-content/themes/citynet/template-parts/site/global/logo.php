<?php
$defaults = array(
    'place' => 'global'
);
$params = wp_parse_args( $args, $defaults );

$type = null;
$value = (int) apply_filters( 'citynet_site_logo_attachment_id', (int) get_field( 'logo', 'option' ), $params );
if ( $value ) {
    $type = 'attachment';
} else {
    $value = (string) apply_filters( 'citynet_site_logo_placeholder', get_bloginfo( 'name' ), $params );
    if ( $value ) $type = 'placeholder';
}
if ( is_null( $type ) ) return;

$classes = array( 'cnwp-site-logo', "cnwp-type-$type", "cnwp-place-{$params['place']}" );
if ( $type === 'placeholder' ) array_push( $classes, 'fs-4', 'fw-bolder', 'text-primary-emphasis' );
$classes = (array) apply_filters( 'citynet_site_logo_classes', $classes, $type, $params );
$classes = $classes? implode( ' ', array_unique( $classes ) ) : '';

$logo = '';
if ( $type === 'attachment' ) {

    $logo = wp_get_attachment_image( $value, 'full', true, array(
        'title' => esc_attr( get_the_title( $value ) ),
        'class' => esc_attr( $classes )
    ) );

} elseif ( $type === 'placeholder' ) {

    $logo = sprintf(
        '<span%s>%s</span>',
        $classes? sprintf( ' class="%s"', esc_attr( $classes ) ) : '',
        esc_html( $value )
    );

}
if ( ! $logo ) return;

$logo = (string) apply_filters( 'citynet_site_logo', $logo, $type, $value, $params );
printf( '<a class="text-decoration-none" href="%s">%s</a>', esc_url( get_home_url() ), $logo );
