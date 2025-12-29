<?php
$content = esc_html__( 'No items found to display!', 'citynet' );
$content = apply_filters( 'citynet_no_items_alert_content', $content );
$type = apply_filters( 'citynet_no_items_alert_type', 'info' );
$classes = apply_filters( 'citynet_no_items_alert_classes', array() );
citynet_alert( $content );