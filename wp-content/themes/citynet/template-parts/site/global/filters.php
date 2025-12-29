<?php
$defaults = array(
    'filters' => array()
);
$params = wp_parse_args( $args, $defaults );
if ( ! $params['filters'] ) return; ?>

<div class="accordion accordion-flush sticky-top z-1 rounded-4 border overflow-hidden" id="filter-boxes-accordion">
    <div id="selected-filters-wrapper" class="accordion-item border-bottom d-none">
        <div class="accordion-header">
            <button class="accordion-button bg-white px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#selected-filter-items"
            aria-expanded="true" aria-controls="selected-filter-items">
                <?php esc_html_e( 'Applied filters', 'citynet' ); ?>
            </button>
        </div>

        <div id="selected-filter-items" class="accordion-collapse collapse show">
            <div class="accordion-body pt-0"></div>
        </div>       
    </div>

    <?php
    foreach ( $params['filters'] as $loop_index => $filter ) {
        if ( ! $filter instanceof CN_Post_Filter ) continue;
        citynet_get_site_template( "global/filter-boxes/{$filter->type}", array(
            'index'  => $loop_index,
            'filter' => $filter
        ) );
    } ?>
</div>