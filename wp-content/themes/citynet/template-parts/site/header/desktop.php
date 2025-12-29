<?php
$defaults = array(
    'in-container' => false
);
$params = wp_parse_args( $args, $defaults );

$container_class = $params['in-container']? 'cnwp-in-container' : 'cnwp-not-in-container';

$header_classes = array( 'cnwp-site-header', 'cnwp-template-desktop', $container_class, 'bg-white' );
if ( $params['in-container'] ) array_push( $header_classes, 'container', 'px-4', 'shadow-sm' );
$header_classes = (array) apply_filters( 'citynet_site_header_classes', $header_classes, 'desktop', $params );
$header_classes = $header_classes? implode( ' ', array_unique( $header_classes ) ) : ''; ?>

<header id="site-header" class="<?php echo esc_attr( $header_classes ); ?>">

    <?php if ( ! $params['in-container'] ) echo '<div class="container px-3">'; ?>
    
        <div class="border-bottom py-3">
            <div class="row gy-3 gy-sm-0 justify-content-sm-between align-items-sm-center">
                <div class="col-12 text-center col-sm-6 text-sm-start col-md-7 col-lg-8 col-xl-9">
                    <?php
                    citynet_get_site_template( 'global/logo', array(
                        'place' => 'desktop-header'
                    ) ); ?>
                </div>

                <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>

    <?php if ( ! $params['in-container'] ) echo '</div>'; ?>

</header>

<?php
$nav_classes = array( 'cnwp-site-nav', 'cnwp-template-desktop', $container_class, 'sticky-top', 'bg-white', 'shadow-sm' );
if ( $params['in-container'] ) array_push( $nav_classes, 'container', 'px-4', 'rounded-bottom-4' );
$nav_classes = (array) apply_filters( 'citynet_site_nav_classes', $nav_classes, 'desktop', $params );
$nav_classes = $nav_classes? implode( ' ', array_unique( $nav_classes ) ) : ''; ?>

<nav class="<?php echo esc_attr( $nav_classes ); ?>">
    <div class="d-flex flex-wrap align-items-center position-relative<?php
        if ( ! $params['in-container'] ) echo ' container px-3';
    ?>">
        <?php
        if ( has_nav_menu( 'header' ) ) {
            $args = array(
                'theme_location' => 'header',
                'container'      => 'ul',
                'menu_id'        => 'header-menu',
                'menu_class'     => "nav cnwp-desktop $container_class",
                'item_class'     => 'nav-item',
                'link_class'     => 'nav-link link-body-emphasis',
                'fallback_cb'    => false
            );
            $args = (array) apply_filters( 'citynet_nav_menu_args', $args, 'site-header', 'desktop' );
            wp_nav_menu( $args );
        } ?>

        <div id="reservation-menu" class="ms-auto py-1"></div>
    </div>
</nav>