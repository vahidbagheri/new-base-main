<?php
class CN_Hotel extends CN_Location {
    public function get_type() {
        return 'hotel';
    }

    public function get_stars() {
        return (int) get_field( 'stars', $this->post->ID );
    }
}