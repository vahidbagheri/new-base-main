<?php
/**
 * Helpers
 *
 * توابع کمکی برای پاکسازی و اعتبارسنجی ورودی‌ها.
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class Helpers {

    /**
     * only_digits
     *
     * هر چیزی غیر از عدد را حذف می‌کند.
     *
     * @param mixed $value ورودی خام (string/int/anything)
     * @return string فقط اعداد
     */
    public static function only_digits($value): string {
        return preg_replace('/\D+/', '', (string)$value);
    }

    /**
     * normalize_mobile
     *
     * نرمال‌سازی شماره موبایل:
     * - حذف کاراکترهای غیر عدد
     * - اگر 10 رقم بود و با 9 شروع شد => 0 اضافه کن (0912...)
     *
     * @param mixed $mobile_raw شماره موبایل خام
     * @return string موبایل نرمال‌شده
     */
    public static function normalize_mobile($mobile_raw): string {
        $m = self::only_digits($mobile_raw);

        if (strlen($m) === 10 && isset($m[0]) && $m[0] === '9') {
            $m = '0' . $m;
        }

        return $m;
    }

    /**
     * is_valid_mobile
     *
     * اعتبارسنجی ساده شماره موبایل ایران:
     * - 11 رقم
     * - شروع با 09
     *
     * @param string $mobile موبایل نرمال‌شده
     * @return bool معتبر است یا نه
     */
    public static function is_valid_mobile(string $mobile): bool {
        // str_starts_with نیازمند PHP 8+ است؛ برای سازگاری: substr
        return (bool)(strlen($mobile) === 11 && substr($mobile, 0, 2) === '09');
    }

    /**
     * safe_redirect_fallback
     *
     * اگر ریدایرکت معتبر نبود، این مقصد را برمی‌گردانیم.
     *
     * @return string آدرس امن پیش‌فرض
     */
    public static function safe_redirect_fallback(): string {
        return home_url('/');
    }
}
