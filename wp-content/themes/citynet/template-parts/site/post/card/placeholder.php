<?php $css_class = citynet_get_card_css_classes( 'post' ); ?>
<div class="col cnwp-placeholder-col d-none" aria-hidden="true">
    <div class="<?php echo esc_attr( implode( ' ', array_unique( $css_class ) ) ) ?>">
        <div class="placeholder-glow">
            <span class="placeholder cnwp-thumbnail col-12"></span>
            <span class="placeholder pt-3 pb-2 m-3 rounded-1 col-10"></span>
        </div>
        <div class="placeholder-glow mt-auto d-flex justify-content-between p-3">
            <span class="placeholder rounded-1 col-5"></span>
            <span class="placeholder rounded-1 col-4"></span>
        </div>
    </div>
</div>