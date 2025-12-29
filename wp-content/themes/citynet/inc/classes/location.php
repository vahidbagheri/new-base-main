<?php
abstract class CN_Location {
    protected $post;

    /**
     * @param WP_Post|int $post
     */
    public function __construct( $post ) {
        $this->post = is_int( $post )? get_post( $post ) : $post;
    }

    /**
     * @return string
     */
    abstract public function get_type();
    
    public function get_country() {
        return get_field( 'country', $this->post->ID );
    }
}