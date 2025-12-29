<?php
if (!defined('ABSPATH')) exit;

$post_id = get_the_ID();
?>

<div class="container my-4">
    <?php citynet_get_site_template('post/single/banner', array('post_id' => $post_id)); ?>
    <div class="row g-4">
        <main class="col-12 col-lg-9">
            <?php citynet_get_site_template('post/single/content-wrapper', array('post_id' => $post_id)); ?>
        </main>

        <aside class="col-12 col-lg-3">
            <?php citynet_get_site_template('post/single/sidebar', array('post_id' => $post_id)); ?>
        </aside>
    </div>
</div>
