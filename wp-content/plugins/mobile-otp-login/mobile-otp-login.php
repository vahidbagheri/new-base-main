<?php
/**
 * Plugin Name:       Mobile OTP Login (Clean & Simple)
 * Plugin URI:        https://example.com
 * Description:       Unified login/register with mobile OTP. Redirects back to previous page. Assets separated (PHP/JS/CSS).
 * Version:           1.0.6
 * Author:            vahid bagheri
 * Text Domain:       motp
 *
 * @package MOTP
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Constants
 */
define('MOTP_PATH', plugin_dir_path(__FILE__));
define('MOTP_URL',  plugin_dir_url(__FILE__));
define('MOTP_VERSION', '1.0.6');

/**
 * Includes
 */
require_once MOTP_PATH . 'includes/helpers.php';
require_once MOTP_PATH . 'includes/class-plugin.php';
require_once MOTP_PATH . 'includes/class-cleanup.php';
require_once MOTP_PATH . 'includes/sms.php';


/**
 * Activation/Deactivation hooks
 */
register_deactivation_hook(__FILE__, 'motp_cleanup_deactivate');

/**
 * Bootstrap plugin
 */
add_action('plugins_loaded', function () {
    \MOTP\Plugin::instance()->init();
});

