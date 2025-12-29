<?php
/**
 * Admin Access Control
 *
 * جلوگیری از ورود کاربران subscriber به wp-admin (تجربه تمیزتر)
 * اگر نمی‌خواهی، می‌توانی این فایل/هوک را حذف کنی.
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class Admin {

    /**
     * register
     *
     * ثبت هوک admin_init.
     *
     * @return void
     */
    public function register(): void {
        add_action('admin_init', [$this, 'block_wp_admin_for_subscribers']);
    }

    /**
     * block_wp_admin_for_subscribers
     *
     * اگر کاربر subscriber بود و وارد wp-admin شد، ریدایرکت به home.
     * (AJAX را دست نمی‌زنیم)
     *
     * @return void
     */
    public function block_wp_admin_for_subscribers(): void {
        if (wp_doing_ajax()) return;
        if (!is_user_logged_in()) return;

        $user = wp_get_current_user();
        if (!$user || empty($user->roles)) return;

        if (in_array('subscriber', $user->roles, true)) {
            wp_safe_redirect(home_url('/'));
            exit;
        }
    }
}
