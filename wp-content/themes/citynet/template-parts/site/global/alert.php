<?php
$defaults = array(
    'content' => '',
    'type'    => 'info',
    'classes' => array()
);
$params = wp_parse_args( $args, $defaults );

if ( ! $params['content'] ) return;

$classes = array( 'alert', "alert-{$params['type']}" );
if ( $params['classes'] ) $classes = array_merge( $classes, $params['classes'] ); ?>

<div class="<?php echo esc_attr( implode( ' ', array_unique( $classes ) ) ); ?>" role="alert">
    <?php echo wp_kses_post( $params['content'] ); ?>
</div>