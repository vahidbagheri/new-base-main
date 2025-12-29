<?php
$defaults = array(
    'in-container' => false
);
$params = wp_parse_args( $args, $defaults );

$container_class = $params['in-container']? 'cnwp-in-container' : 'cnwp-not-in-container';

$header_classes = array( 'cnwp-site-header', 'cnwp-template-mobile', $container_class, 'navbar', 'sticky-top', 'py-0' );
if ( $params['in-container'] ) array_push( $header_classes, 'container', 'px-4' );
$header_classes = (array) apply_filters( 'citynet_site_header_classes', $header_classes, 'mobile', $params );
$header_classes = $header_classes? implode( ' ', array_unique( $header_classes ) ) : ''; ?>

<header id="site-header" class="<?php echo esc_attr( $header_classes ); ?>">
    <div class="container-fluid bg-white shadow-sm py-3 px-4<?php
        if ( $params['in-container'] ) echo ' rounded-bottom-4';
    ?>">
        <div class="row w-100 gx-0 align-items-center">
            <div class="col-10 col-sm-6 col-md-7 col-lg-8 col-xl-9">
                <?php
                citynet_get_site_template( 'global/logo', array(
                    'place' => 'mobile-header'
                ) ); ?>
            </div>

            <div class="col-2 text-end col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#site-mobile-header" aria-controls="site-mobile-header"
                    aria-label="<?php esc_attr_e( 'Toggle navigation', 'citynet' ); ?>"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
            id="site-mobile-header" aria-labelledby="site-mobile-header-label"
        >
            <div class="offcanvas-header">
                <span class="offcanvas-title fw-bold" id="site-mobile-header-label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                    aria-label="<?php esc_attr_e( 'Close', 'citynet' ); ?>"
                ></button>
            </div>

            <nav class="offcanvas-body">
                <?php
                get_search_form();

                if ( has_nav_menu( 'header' ) ) {
                    $args = array(
                        'theme_location' => 'header',
                        'container'      => 'ul',
                        'menu_id'        => 'header-menu',
                        'menu_class'     => "navbar-nav justify-content-end flex-grow-1 mt-3 cnwp-mobile $container_class",
                        'item_class'     => 'nav-item',
                        'link_class'     => 'nav-link text-truncate',
                        'fallback_cb'    => false
                    );
                    $args = (array) apply_filters( 'citynet_nav_menu_args', $args, 'site-header', 'mobile' );
                    wp_nav_menu( $args );
                } ?>
            </nav>

        </div>
    </div>
</header>