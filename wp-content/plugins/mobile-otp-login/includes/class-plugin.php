<?php
/**
 * Plugin Bootstrap/Loader
 *
 * این کلاس همه بخش‌ها را بالا می‌آورد:
 * - shortcode
 * - assets
 * - ajax
 * - admin blocking
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

require_once MOTP_PATH . 'includes/class-otp.php';
require_once MOTP_PATH . 'includes/class-sms.php';
require_once MOTP_PATH . 'includes/class-auth.php';
require_once MOTP_PATH . 'includes/class-redirect.php';
require_once MOTP_PATH . 'includes/class-ajax.php';
require_once MOTP_PATH . 'includes/class-admin.php';

final class Plugin {

    /**
     * @var Plugin|null
     */
    private static $instance = null;

    /**
     * instance
     *
     * Singleton instance.
     *
     * @return Plugin
     */
    public static function instance(): self {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    /**
     * init
     *
     * راه‌اندازی هوک‌ها.
     *
     * @return void
     */
    public function init(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('mobile_otp_login', [$this, 'shortcode_form']);

        (new Ajax())->register();
        (new Admin())->register();
    }

    /**
     * enqueue_assets
     *
     * لود کردن CSS و JS پلاگین.
     *
     * @return void
     */
    public function enqueue_assets(): void {
        wp_enqueue_style(
            'motp-css',
            MOTP_URL . 'assets/css/app.css',
            [],
            MOTP_VERSION
        );

        wp_enqueue_script(
            'motp-js',
            MOTP_URL . 'assets/js/app.js',
            [],
            MOTP_VERSION,
            true
        );

        wp_localize_script('motp-js', 'MOTP', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('motp_nonce'),
        ]);
    }

    /**
     * shortcode_form
     *
     * خروجی فرم با template جداگانه.
     *
     * @return string HTML
     */
    public function shortcode_form(): string {
        ob_start();
        include MOTP_PATH . 'templates/form.php';
        return (string) ob_get_clean();
    }
}
