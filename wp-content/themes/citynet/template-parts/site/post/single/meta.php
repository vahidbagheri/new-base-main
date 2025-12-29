<?php
$post_id = isset($args['post_id']) ? $args['post_id'] : get_the_ID();
$badges = citynet_get_categories_badges($post_id);
$reading_time = citynet_estimate_read_time(get_the_content($post_id));
?>

<header class="post-meta d-flex flex-wrap align-items-center gap-2 mb-3">
    <?php foreach ($badges as $badge): ?>
        <span class="badge bg-dark text-white fw-semibold px-3 py-2 rounded-pill">
            <?php echo esc_html($badge); ?>
        </span>
    <?php endforeach; ?>
    <span class="reading-time d-flex align-items-center text-secondary small px-3 py-1 rounded-pill bg-light">
        <i class="bi bi-clock me-1"></i>
        <?php echo sprintf(esc_html__('%s min read', 'citynet'), $reading_time); ?>
    </span>
</header>

<h2 class="fw-bold mb-2"><?php echo esc_html(get_the_title()); ?></h2>

<p class="text-muted mb-3">
    <?php
    if (has_excerpt()) {
        echo get_the_excerpt();
    } else {
        echo wp_trim_words(strip_tags(get_the_content()), 30, '...');
    }
    ?>
</p>
