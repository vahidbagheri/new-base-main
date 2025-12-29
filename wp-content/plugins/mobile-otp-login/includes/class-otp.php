<?php
/**
 * OTP Service
 *
 * وظایف:
 * - تولید OTP
 * - ذخیره امن OTP (هش)
 * - انقضا
 * - rate limit برای جلوگیری از اسپم
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class OTP {

    /**
     * @var int مدت اعتبار OTP (ثانیه)
     */
    private int $ttl_seconds = 120;

    /**
     * @var int مدت محدودیت ارسال مجدد (ثانیه)
     */
    private int $throttle_seconds = 60;

    /**
     * otp_key
     *
     * کلید transient برای OTP این موبایل.
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return string کلید transient
     */
    private function otp_key(string $mobile): string {
        return 'motp_otp_' . md5($mobile);
    }

    /**
     * throttle_key
     *
     * کلید transient برای throttle این موبایل.
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return string کلید transient
     */
    private function throttle_key(string $mobile): string {
        return 'motp_throttle_' . md5($mobile);
    }

    /**
     * generate
     *
     * تولید OTP شش رقمی.
     *
     * @return string کد 6 رقمی
     */
    public function generate(): string {
        return (string) random_int(100000, 999999);
    }

    /**
     * throttle_or_fail
     *
     * اگر کاربر زودتر از حد مجاز درخواست کد بدهد،
     * با wp_send_json_error پاسخ می‌دهد و اجرای PHP را تمام می‌کند.
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return void
     */
    public function throttle_or_fail(string $mobile): void {
        $key = $this->throttle_key($mobile);

        if (get_transient($key)) {
            wp_send_json_error(['message' => 'لطفاً کمی صبر کنید و دوباره تلاش کنید.']);
        }

        set_transient($key, time(), $this->throttle_seconds);
    }

    /**
     * issue
     *
     * صدور OTP:
     * - تولید کد
     * - هش کردن کد
     * - ذخیره hash و زمان انقضا در transient
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return string خود OTP برای ارسال پیامک
     */
    public function issue(string $mobile): string {
        $otp = $this->generate();

        $payload = [
            'hash' => wp_hash_password($otp),
            'exp'  => time() + $this->ttl_seconds,
        ];

        set_transient($this->otp_key($mobile), $payload, $this->ttl_seconds + 30);

        return $otp;
    }

    /**
     * verify
     *
     * بررسی OTP:
     * - وجود transient
     * - چک انقضا
     * - چک هش
     * - در صورت موفقیت: حذف transient (مصرف یکبار)
     *
     * @param string $mobile موبایل نرمال‌شده
     * @param string $otp کد وارد شده
     * @return bool درست است یا نه
     */
    public function verify(string $mobile, string $otp): bool {
        $otp = Helpers::only_digits($otp);

        $stored = get_transient($this->otp_key($mobile));
        if (!$stored || empty($stored['hash']) || empty($stored['exp'])) {
            return false;
        }

        if (time() > (int)$stored['exp']) {
            delete_transient($this->otp_key($mobile));
            return false;
        }

        $ok = wp_check_password($otp, $stored['hash']);

        if ($ok) {
            delete_transient($this->otp_key($mobile));
        }

        return (bool)$ok;
    }
}
