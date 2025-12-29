<?php
if (!defined('ABSPATH')) exit;

function citynet_get_post_banner($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $device  = function_exists('citynet_get_device') ? citynet_get_device() : '';
    $banner  = get_field("{$device}-top-banner", $post_id);
    return $banner ? $banner : (has_post_thumbnail($post_id) ? get_post_thumbnail_id($post_id) : null);
}

function citynet_get_categories_badges($post_id = null) {
    $categories = get_the_category($post_id);
    return array_map(function($cat) {
        return esc_html($cat->name);
    }, $categories ? $categories : array());
}

function citynet_get_related_posts($post_id, $limit = 5) {
    $cats = wp_get_post_categories($post_id);
    return $cats ? new WP_Query(array(
        'category__in' => $cats,
        'post__not_in' => array($post_id),
        'posts_per_page' => $limit,
        'ignore_sticky_posts' => 1
    )) : null;
}

function citynet_get_latest_posts($post_id, $limit = 5) {
    return new WP_Query(array(
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'ignore_sticky_posts' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
}
