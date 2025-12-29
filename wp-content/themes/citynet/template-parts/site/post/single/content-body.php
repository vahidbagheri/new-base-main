<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$content = apply_filters('the_content', get_the_content());

preg_match_all('/<h([2-3])([^>]*)>(.*?)<\/h[2-3]>/', $content, $matches, PREG_SET_ORDER);
$headings = array();

foreach ($matches as $match) {
    $tag  = $match[1];
    $text = strip_tags($match[3]);
    $id   = sanitize_title($text);

    if (strpos($match[2], 'id=') === false) {
        $new_heading = sprintf('<h%s id="%s"%s>%s</h%s>', $tag, $id, $match[2], $match[3], $tag);
        $content = str_replace($match[0], $new_heading, $content);
    }

    $headings[] = array(
        'text' => $text,
        'id'   => $id
    );
}

if (!empty($headings)) {
    citynet_get_site_template('post/single/toc', array('headings' => $headings));
}

echo $content;
