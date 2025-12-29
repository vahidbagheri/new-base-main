<?php
// Callback for ajax request of load more posts cards
function cn_load_more_posts_cards() {
	if ( isset( $_REQUEST, $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_text_field( $_REQUEST['nonce'] ), 'load-more' ) ) :
        unset( $_REQUEST['action'], $_REQUEST['nonce'] );

        $template_args = array();
        if ( isset( $_REQUEST['template-args'] ) ) {
            $template_args = (array) $_REQUEST['template-args'];
            unset( $_REQUEST['template-args'] );
        }

        $filters = array();
        if ( isset( $_REQUEST['filters'] ) && $_REQUEST['filters'] ) {
            $filters = (array) $_REQUEST['filters'];
            unset( $_REQUEST['filters'] );
        }

        $args = $_REQUEST;

        if ( $filters ) {
            if ( ! isset( $args['tax_query'] ) ) $args['tax_query'] = array('relation' => 'OR');

            foreach ( $filters as $item_text ) {
                list( $filter_type, $filter_value, $item_value ) = explode( '|', $item_text );

                if ( $filter_type === 'taxonomy' ) {
                    if ( isset( $args['tax_query'][ $filter_value ] ) ) {
                        if ( ! in_array( $item_value, $args['tax_query'][ $filter_value ]['terms'] ) ) $args['tax_query'][ $filter_value ]['terms'][] = (int) $item_value;
                    } else {
                        $args['tax_query'][ $filter_value ] = array(
                            'taxonomy' => $filter_value,
                            'field'    => 'term_id',
                            'terms'    => array( (int) $item_value )
                        );
                    }
                }
            }

            if ( ! $args['tax_query'] ) unset( $args['tax_query'] );
        }

        $posts = citynet_get_posts( $args );

        $result = array(
            'more'  => $posts['more'],
            'items' => array()
        );

        if ( ! $posts['items'] ) wp_send_json_success( $result );

        global $post;
        foreach ( $posts['items'] as $post ) {
            ob_start();
            $post_type = get_post_type( $post );
            citynet_get_site_template( "$post_type/card", $template_args, '<div class="col">', '</div>' );
            $result['items'][] = ob_get_clean();
        }

        wp_send_json_success( $result );
	endif;
}
add_action( 'wp_ajax_nopriv_cn_load_more_posts_cards', 'cn_load_more_posts_cards' );
add_action( 'wp_ajax_cn_load_more_posts_cards', 'cn_load_more_posts_cards' );