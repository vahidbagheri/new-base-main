<span class="read-time text-body-emphasis">
    <?php
    $read_time = citynet_estimate_read_time( get_the_content() );
    printf(
        '%s: ',
        esc_html_e( 'Read time', 'citynet' )
    );
    printf(
        _n( '%s Minute', '%s Minutes', $read_time, 'citynet' ),
        citynet_is_fa()? citynet_convert_number( $read_time, 'per' ) : $read_time
    ); ?>
</span>