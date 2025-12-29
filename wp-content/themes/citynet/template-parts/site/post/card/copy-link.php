<?php
$link = wp_get_shortlink();
if ( ! $link ) {
    $link = get_permalink();
    if ( ! $link ) return;
} ?>

<div class="post-copy-link position-absolute top-0 end-0 p-3" itemprop="about" itemscope itemtype="https://schema.org/Thing">
    <button class="copy-link-button btn btn-light btn-lg px-2 py-1" type="button" data-link="<?php echo esc_url( $link ); ?>"
        title="<?php esc_attr_e( 'Copy link', 'citynet' ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'citynet' ); ?>"
        itemscope itemtype="https://schema.org/WebPageElement" itemprop="copyLink"
    >
        <i class="icon-link align-middle"></i>
    </button>
</div>