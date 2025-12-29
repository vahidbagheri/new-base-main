<?php
/**
 * Redirect Strategy
 *
 * مقصد بعد از لاگین: همان صفحه‌ای که کاربر قبل از لاگین بوده.
 * برای دقت بیشتر، redirect را از فرم (hidden input) می‌گیریم.
 * و برای امنیت، فقط اجازه ریدایرکت به دامنه خود سایت می‌دهیم.
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class Redirect {

    /**
     * resolve
     *
     * تعیین مقصد:
     * - اگر redirect از POST آمد و امن بود، همان
     * - وگرنه wp_get_referer() اگر امن بود
     * - وگرنه home
     *
     * همچنین با فیلتر motp_redirect_url قابل تغییر است.
     *
     * @param string|null $posted_redirect redirect دریافتی از فرم (اختیاری)
     * @return string مقصد نهایی
     */
    public function resolve(?string $posted_redirect = null): string {
        $candidate = $posted_redirect ? esc_url_raw($posted_redirect) : '';

        if ($candidate && $this->is_safe_url($candidate)) {
            return (string) apply_filters('motp_redirect_url', $candidate);
        }

        $ref = wp_get_referer();
        if ($ref && $this->is_safe_url($ref)) {
            return (string) apply_filters('motp_redirect_url', $ref);
        }

        return (string) apply_filters('motp_redirect_url', Helpers::safe_redirect_fallback());
    }

    /**
     * is_safe_url
     *
     * جلوگیری از Open Redirect:
     * فقط اجازه ریدایرکت به دامنه خود سایت.
     * - اگر URL relative باشد => امن
     * - اگر host برابر host سایت باشد => امن
     *
     * @param string $url آدرس مقصد
     * @return bool امن است یا نه
     */
    private function is_safe_url(string $url): bool {
        $home_host = wp_parse_url(home_url(), PHP_URL_HOST);
        $ref_host  = wp_parse_url($url, PHP_URL_HOST);

        if (!$ref_host) return true; // relative URL

        return strtolower((string)$home_host) === strtolower((string)$ref_host);
    }
}
