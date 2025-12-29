<?php
/**
 * Auth Service
 *
 * وظایف:
 * - پیدا کردن کاربر با موبایل (user_meta)
 * - ساخت کاربر در صورت نبودن
 * - لاگین کاربر (کوکی استاندارد وردپرس)
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class Auth {

    /**
     * @var string meta_key شماره موبایل
     */
    public const META_MOBILE = 'mobile';

    /**
     * find_user_by_mobile
     *
     * جستجوی کاربر با user_meta (mobile).
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return \WP_User|null کاربر یا null
     */
    public function find_user_by_mobile(string $mobile) {
        $users = get_users([
            'meta_key'   => self::META_MOBILE,
            'meta_value' => $mobile,
            'number'     => 1,
            'fields'     => ['ID'],
        ]);

        if (!$users) return null;

        return get_user_by('id', (int)$users[0]->ID);
    }

    /**
     * create_user_for_mobile
     *
     * ساخت کاربر جدید:
     * - user_login یکتا (u_0912...)
     * - پسورد رندوم (ورود با OTP)
     * - role پیش‌فرض: subscriber
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return \WP_User|\WP_Error
     */
    public function create_user_for_mobile(string $mobile) {
        $base_login = 'u_' . $mobile;
        $login = $base_login;
        $i = 1;

        while (username_exists($login)) {
            $login = $base_login . '_' . $i;
            $i++;
        }

        $pass = wp_generate_password(24, true, true);

        $user_id = wp_insert_user([
            'user_login' => $login,
            'user_pass'  => $pass,
            'role'       => 'subscriber',
        ]);

        if (is_wp_error($user_id)) {
            return $user_id;
        }

        update_user_meta($user_id, self::META_MOBILE, $mobile);

        return get_user_by('id', (int)$user_id);
    }

    /**
     * get_or_create_user
     *
     * اگر کاربر با این موبایل وجود داشت برمی‌گرداند،
     * اگر نبود می‌سازد.
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return \WP_User|\WP_Error
     */
    public function get_or_create_user(string $mobile) {
        $user = $this->find_user_by_mobile($mobile);
        if ($user) return $user;

        return $this->create_user_for_mobile($mobile);
    }

    /**
     * login
     *
     * لاگین استاندارد وردپرس با کوکی:
     * - wp_set_current_user
     * - wp_set_auth_cookie
     *
     * @param int $user_id آیدی کاربر
     * @return void
     */
    public function login(int $user_id): void {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', wp_get_current_user()->user_login, wp_get_current_user());
    }
}
