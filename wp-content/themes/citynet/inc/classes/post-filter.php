<?php
class CN_Post_Filter {
    public $type;
    public $name;
    public $multi_select;
    public $has_search;
    private $label;
    private $object;

    public function __construct( string $type, string $name, string $label = '', bool $multi_select = true, bool $has_search = true ) {
        $this->type = $type;
        $this->name = $name;
        $this->multi_select = $multi_select;
        $this->has_search = $has_search;
        $this->object = $this->fetch_object();
        $this->label = $label? $label : $this->fetch_label();
    }

    public function get_object() {
        return $this->object;
    }

    public function get_label() {
        return $this->label;
    }

    public function search_box() {
        if ( ! $this->object || ! $this->has_search ) return;

        printf(
            '<input class="search-items form-control mb-2" type="text" id="%1$s" placeholder="%2$s..." aria-label="%2$s">',
            "{$this->type}-{$this->name}-search-filter-items",
            esc_attr__( 'Search', 'citynet' )
        );
    }

    public function list_items() {
        if ( ! $this->object ) return;

        if ( $this->type === 'taxonomy' ) {
            $this->list_taxonomy_terms();
        }
    }

    private function fetch_object() {
        if ( ! $this->name ) return false;

        if ( $this->type === 'taxonomy' ) {
            $taxonomy = get_taxonomy( $this->name );
            return $taxonomy;
        }

        return false;
    }

    private function fetch_label() {
        if ( ! $this->object ) return '';

        if ( $this->type === 'taxonomy' ) {
            return $this->object->label;
        }

        return '';
    }

    private function list_taxonomy_terms( int $parent = 0 ) {
        if ( $this->type !== 'taxonomy' || ! $this->object ) return;

        $query = new WP_Term_Query( [
            'taxonomy' => $this->name,
            'parent'   => $parent,
            'orderby'  => 'name',
            'order'    => 'ASC',
            'fields'   => 'id=>name'
        ] );
        if ( ! $query->terms ) return;

        printf(
            '<ul class="list-unstyled overflow-y-auto%s mb-0">',
            $parent? ' ms-3' : ''
        );

        foreach ( $query->terms as $id => $name ) {
            echo '<li>';
            echo $this->get_option_html( $name, $id );
            $this->list_taxonomy_terms( $id );
            echo '</li>';
        }

        echo '</ul>';
    }

    // private function get_option_html( string $title, string $value ) {
    //     if ( ! $title || ! $value ) return '';

    //     $key = sprintf(
    //         'filter-item-term-%s',
    //         sanitize_key( str_replace( ' ', '-', $value ) )
    //     );

    //     $html = '<div class="form-check my-1">
    //         <input class="form-check-input" type="checkbox" data-title="' . esc_attr( $title ) . '" data-search="' . esc_attr( strtolower( $title ) ) . '" value="' . esc_attr( $value ) . '" id="' . esc_attr( $key ) . '">
    //         <label class="form-check-label" for="' . esc_attr( $key ) . '">' . esc_html( $title ) . '</label>
    //     </div>';

    //     return $html;
    // }

    private function get_option_html( string $title, string $value ) {
        if ( ! $title || ! $value ) return '';

        $current_term = get_queried_object();
        $checked = '';
        $data_active = '';

        // 🔹 اگر دسته فعلی با این term برابر بود → فعال کن
        if ( $current_term && isset( $current_term->term_id ) && (int) $current_term->term_id === (int) $value ) {
            $checked = ' checked="checked"';
            $data_active = ' data-active="true"'; // 👈 برای هماهنگی با JS
        }

        $key = sprintf(
            'filter-item-term-%s',
            sanitize_key( str_replace( ' ', '-', $value ) )
        );

        $html = '<div class="form-check my-1">
            <input class="form-check-input" type="checkbox" data-title="' . esc_attr( $title ) . '" data-search="' . esc_attr( strtolower( $title ) ) . '" value="' . esc_attr( $value ) . '" id="' . esc_attr( $key ) . '"' . $checked . $data_active . '>
            <label class="form-check-label" for="' . esc_attr( $key ) . '">' . esc_html( $title ) . '</label>
        </div>';

        return $html;
    }
}