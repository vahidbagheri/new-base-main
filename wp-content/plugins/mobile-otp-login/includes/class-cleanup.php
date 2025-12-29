<?php
/**
 * Cleanup expired MOTP transients to prevent wp_options bloat.
 *
 * What it does:
 * - Deletes expired transients ONLY for this plugin (prefix: motp_)
 * - Runs hourly via WP-Cron
 * - Also runs "probabilistically" on page loads (default 1%) as a safety net
 *
 * Notes:
 * - If you use Redis/Memcached object cache, transients usually won't sit in wp_options.
 * - This targets only wp_options based transients, safe and lightweight.
 */

if (!defined('ABSPATH')) exit;

add_action('init', 'motp_cleanup_maybe_boot');
add_action('motp_cleanup_event', 'motp_cleanup_expired_transients');

/**
 * Boot cleanup:
 * - schedule hourly cron
 * - run probabilistic cleanup (safety net)
 */
function motp_cleanup_maybe_boot() {
    // 1) Schedule hourly cleanup
    if (!wp_next_scheduled('motp_cleanup_event')) {
        wp_schedule_event(time() + 300, 'hourly', 'motp_cleanup_event'); // start in 5 minutes
    }

    // 2) Probabilistic cleanup: 1% of requests (adjust if needed)
    if (mt_rand(1, 100) === 1) {
        motp_cleanup_expired_transients();
    }
}

/**
 * Remove expired transients in wp_options for this plugin (motp_ prefix).
 *
 * Deletes:
 * - _transient_timeout_motp_*
 * - _transient_motp_* (matching the expired timeout rows)
 */
function motp_cleanup_expired_transients() {
    global $wpdb;

    // Don't run too frequently in the same request burst
    $lock_key = 'motp_cleanup_lock';
    if (get_transient($lock_key)) return;
    set_transient($lock_key, 1, 5 * MINUTE_IN_SECONDS);

    $now = time();
    $options = $wpdb->options;

    // Delete expired timeout rows + their value rows in one query (MySQL)
    $sql = "
        DELETE t, v
        FROM {$options} t
        LEFT JOIN {$options} v
          ON v.option_name = REPLACE(t.option_name, '_transient_timeout_', '_transient_')
        WHERE t.option_name LIKE %s
          AND CAST(t.option_value AS UNSIGNED) < %d
    ";

    $like = $wpdb->esc_like('_transient_timeout_motp_') . '%';
    $wpdb->query($wpdb->prepare($sql, $like, $now));
}

/**
 * Optional: Clear scheduled event on plugin deactivation.
 * Put this in your deactivation hook.
 */
function motp_cleanup_deactivate() {
    $ts = wp_next_scheduled('motp_cleanup_event');
    if ($ts) wp_unschedule_event($ts, 'motp_cleanup_event');
}
