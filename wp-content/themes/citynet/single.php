<?php
/**
 * Universal Single Template Loader (Clean Version)
 */

if (!defined('ABSPATH')) exit;

get_header();

$post_type = get_post_type();
$template_base = get_template_directory() . '/template-parts/site/';
$template_file = "{$template_base}{$post_type}/single/index.php";
$default_file  = "{$template_base}global/single-default.php";

if (file_exists($template_file)) {
    include $template_file;
} elseif (file_exists($default_file)) {
    include $default_file;
} else { ?>
    <div class="container my-5">
        <h2><?php esc_html_e('Template not found', 'citynet'); ?></h2>
        <p><?php printf(esc_html__('No single template exists for post type: %s', 'citynet'), esc_html($post_type)); ?></p>
    </div>
<?php }

get_footer();
