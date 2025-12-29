<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
?>
<div class="bg-white rounded-4 shadow-sm p-4 mb-4">
    <?php citynet_get_site_template(
        array('post/single/meta', 'post/single/content-body', 'post/single/comments'),
        array('post_id' => $post_id)
    ); ?>
</div>
