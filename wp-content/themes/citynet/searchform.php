<form class="d-flex" role="search">
    <input type="search" class="form-control me-2" name="s" autocomplete="off" placeholder="<?php
        printf( '%s...', esc_attr__( 'Search', 'citynet' ) );
    ?>" aria-label="<?php esc_attr_e( 'Search', 'citynet' ) ?>" required>
    
    <button class="btn btn-outline-secondary" type="submit"><?php esc_html_e( 'Search', 'citynet' ) ?></button>
</form>