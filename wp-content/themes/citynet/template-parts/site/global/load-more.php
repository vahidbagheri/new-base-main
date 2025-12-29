<?php
$caption = (string) apply_filters( 'citynet_load_more_cards_button_caption', esc_html__( 'More items', 'citynet' ) );

$args['query']['filters'] = array();
$args['query']['action'] = 'cn_load_more_posts_cards';
$args['query']['nonce'] = wp_create_nonce( 'load-more' );

$query_posts = array(
    'count' => $args['more'],
    'ppp'   => (int) get_option( 'posts_per_page' ),
    'query' => $args['query']
); ?>

<div class="d-flex justify-content-center mt-4">
    <button id="load-more-cards" class="btn btn-secondary <?php if ( ! $args['more'] ) echo ' d-none'; ?>"
    data-query="<?php echo esc_attr( wp_json_encode( $query_posts ) ); ?>">
        <?php echo $caption; ?>
    </button>
</div>