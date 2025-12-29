<?php
/**
 * AJAX Handlers
 *
 * دو endpoint:
 * - motp_send_otp
 * - motp_verify_otp
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class Ajax {

    /**
     * register
     *
     * ثبت اکشن‌های AJAX برای کاربران لاگین‌نشده.
     *
     * @return void
     */
    public function register(): void {
        add_action('wp_ajax_nopriv_motp_send_otp',   [$this, 'send_otp']);
        add_action('wp_ajax_nopriv_motp_verify_otp', [$this, 'verify_otp']);
    }

    /**
     * send_otp
     *
     * ورودی: mobile
     * خروجی: success/error
     *
     * منطق:
     * - nonce check
     * - normalize + validate mobile
     * - rate limit
     * - issue OTP
     * - send SMS
     *
     * @return void (JSON response)
     */
    public function send_otp(): void {
        if (!check_ajax_referer('motp_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'درخواست نامعتبر است.']);
        }

        $mobile = Helpers::normalize_mobile($_POST['mobile'] ?? '');
        if (!Helpers::is_valid_mobile($mobile)) {
            wp_send_json_error(['message' => 'شماره موبایل معتبر نیست.']);
        }

        $otpService = new OTP();
        $otpService->throttle_or_fail($mobile);

        $otp = $otpService->issue($mobile);

        $sms = new SMS();
        $sent = $sms->send($mobile, $otp);

        if (is_wp_error($sent)) {
            wp_send_json_error(['message' => $sent->get_error_message()]);
        }

        // پیام یکسان برای جلوگیری از لو رفتن اطلاعات
        wp_send_json_success(['message' => 'کد تایید ارسال شد.']);
    }

    /**
     * verify_otp
     *
     * ورودی: mobile, otp, redirect(optional)
     * خروجی: success + redirect URL
     *
     * منطق:
     * - nonce check
     * - normalize + validate mobile
     * - verify OTP
     * - get_or_create user
     * - login
     * - resolve redirect (previous page)
     *
     * @return void (JSON response)
     */
    public function verify_otp(): void {
        if (!check_ajax_referer('motp_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'درخواست نامعتبر است.']);
        }

        $mobile   = Helpers::normalize_mobile($_POST['mobile'] ?? '');
        $otp      = Helpers::only_digits($_POST['otp'] ?? '');
        $redirect = isset($_POST['redirect']) ? (string)$_POST['redirect'] : null;

        if (!Helpers::is_valid_mobile($mobile)) {
            wp_send_json_error(['message' => 'شماره موبایل معتبر نیست.']);
        }

        if (!$otp || strlen($otp) < 4) {
            wp_send_json_error(['message' => 'کد نامعتبر است.']);
        }

        $otpService = new OTP();
        if (!$otpService->verify($mobile, $otp)) {
            wp_send_json_error(['message' => 'کد اشتباه یا منقضی شده است.']);
        }

        $auth = new Auth();
        $user = $auth->get_or_create_user($mobile);

        if (is_wp_error($user)) {
            wp_send_json_error(['message' => $user->get_error_message()]);
        }

        $auth->login((int)$user->ID);

        $finalRedirect = (new Redirect())->resolve($redirect);

        wp_send_json_success([
            'message'  => 'با موفقیت وارد شدید.',
            'redirect' => $finalRedirect,
        ]);
    }
}
