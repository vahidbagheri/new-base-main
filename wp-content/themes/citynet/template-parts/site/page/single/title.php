<?php
$post_id = 0;
$title = '';

if ( is_home() ) {
    $post_id = get_option( 'page_for_posts' );
} elseif ( ! is_page() ) {
    $post_id = 'option';
}

if ( $post_id === 'option' ) {
    $post_type = '';
    $title = '';
} else {
    $title = get_the_title( $post_id );
}

$device = citynet_get_device();
if ( ! $post_id ) $post_id = false;
$banner = get_field( "{$device}-top-banner", $post_id );

citynet_breadcrumbs();

if ( $banner ) { ?>
    <div class="top-banner-wrapper position-relative rounded-4 overflow-hidden text-white text-center d-flex flex-column justify-content-center align-items-center mb-4">
        <?php echo wp_get_attachment_image( $banner, 'full', false, array(
            'title' => esc_attr( $title ),
            'class' => 'w-100'
        ) ); ?>
        <div class="position-absolute w-75 z-1">
            <h1 class="page-title fw-bold mb-0"><?php echo esc_html( $title ); ?></h1>
        </div>
    </div>
<?php } else { ?>
    <h1 class="page-title text-center fw-bold mb-4"><?php echo esc_html( $title ); ?></h1>
<?php } ?>