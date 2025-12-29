<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$title   = get_the_title($post_id);
$banner  = citynet_get_post_banner($post_id);
?>

<?php if ($banner): ?>
    <header class="top-banner-wrapper position-relative rounded-4 overflow-hidden text-white text-center d-flex flex-column justify-content-center align-items-center mb-4" role="banner">
        <figure class="top-banner-image m-0">
            <?php
            echo wp_get_attachment_image(
                $banner,
                'full',
                false,
                array(
                    'title'         => esc_attr($title),
                    'class'         => 'w-100 object-cover',
                    'fetchpriority' => 'high',
                    'decoding'      => 'async',
                    'loading'       => 'eager'
                )
            );
            ?>
        </figure>
        <div class="position-absolute w-75 z-1">
            <h1 class="page-title fw-bold mb-0"><?php echo esc_html($title); ?></h1>
        </div>
    </header>
<?php else: ?>
    <header class="post-header text-center mb-4">
        <h1 class="page-title fw-bold mb-0"><?php echo esc_html($title); ?></h1>
    </header>
<?php endif; ?>
