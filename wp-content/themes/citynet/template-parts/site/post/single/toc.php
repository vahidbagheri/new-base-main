<?php
$headings = isset($args['headings']) ? $args['headings'] : array();
if (!empty($headings)):
?>
<div class="accordion rounded-4 shadow-sm mb-3 overflow-hidden bg-white border border-light-subtle" id="accordionToc">
    <div class="accordion-item border-0">
        <h2 class="accordion-header">
            <button class="accordion-button bg-light text-dark fw-semibold py-3 px-4" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseToc" aria-expanded="true">
                <i class="bi bi-list-ul me-2 text-primary"></i>
                <?php echo esc_html__('Table of Contents', 'citynet'); ?>
            </button>
        </h2>
        <div id="collapseToc" class="accordion-collapse collapse show">
            <div class="accordion-body bg-white p-3">
                <div class="row row-cols-1 row-cols-md-2 g-2">
                    <?php foreach ($headings as $heading): ?>
                        <div class="col">
                            <a href="#<?php echo esc_attr($heading['id']); ?>"
                               class="d-flex align-items-center gap-2 small text-dark text-decoration-none lh-sm p-2 rounded border border-light-subtle">
                                <i class="bi bi-bookmark text-primary fs-5"></i>
                                <span><?php echo esc_html($heading['text']); ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
