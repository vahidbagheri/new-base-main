<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
comments_template();
