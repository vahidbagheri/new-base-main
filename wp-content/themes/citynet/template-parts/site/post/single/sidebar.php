<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
?>
<div class="bg-white rounded-4 shadow-sm">
    <?php citynet_get_site_template('post/single/related', array('post_id' => $post_id)); ?>
</div>

<div class="bg-white rounded-4 shadow-sm mt-4">
    <?php citynet_get_site_template('post/single/latest', array('post_id' => $post_id)); ?>
</div>

<div class="mt-4">
    <?php citynet_get_site_template('post/single/ads', array('post_id' => $post_id)); ?>
</div>
