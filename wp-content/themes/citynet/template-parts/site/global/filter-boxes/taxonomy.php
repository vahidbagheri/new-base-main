<?php
$defaults = array(
    'index'  => 0,
    'filter' => null
);
$params = wp_parse_args( $args, $defaults );
if ( ! $params['filter'] ) return;

/** @var CN_Post_Filter $filter */
$filter = $params['filter'];
if ( ! $filter->get_object() instanceof WP_Taxonomy ) return;

$section_id = "{$filter->name}-filter-items"; ?>

<div class="filter-wrapper accordion-item<?php if ( $params['index'] ) echo ' border-top'; ?>"
    data-type="taxonomy" data-value="<?php echo esc_attr( $filter->name ); ?>" data-title="<?php echo esc_attr( $filter->get_label() ); ?>">
    <div class="accordion-header">
        <button class="accordion-button bg-white px-3 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $section_id ); ?>"
        aria-expanded="true" aria-controls="<?php echo esc_attr( $section_id ); ?>">
            <?php echo esc_html( $filter->get_label() ); ?>
        </button>
    </div>

    <div id="<?php echo esc_attr( $section_id ); ?>" class="accordion-collapse collapse show">
        <div class="accordion-body pt-0">
            <?php
            $filter->search_box();
            $filter->list_items(); ?>
        </div>
    </div>       
</div>