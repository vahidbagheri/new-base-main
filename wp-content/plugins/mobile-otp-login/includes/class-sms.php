<?php
/**
 * SMS Service (Wrapper)
 *
 * این کلاس فقط ارسال پیامک را مدیریت می‌کند.
 * تابع واقعی ارسال پیامک از شما:
 *   voiceweb_send_sms($message, $mobile, $for_customer)
 *
 * @package MOTP
 */

namespace MOTP;

if (!defined('ABSPATH')) exit;

class SMS {

    /**
     * send
     *
     * ارسال پیامک OTP.
     *
     * @param string $mobile موبایل نرمال‌شده
     * @param string $otp کد OTP
     * @return mixed|\WP_Error پاسخ پنل پیامک یا WP_Error
     */
    public function send(string $mobile, string $otp) {
        if (!function_exists('voiceweb_send_sms')) {
            return new \WP_Error('motp_sms_missing', 'تابع ارسال پیامک (voiceweb_send_sms) پیدا نشد.');
        }

        $message = "کد ورود شما: {$otp}\nاعتبار: ۲ دقیقه";

        return voiceweb_send_sms($message, $mobile, true);
    }
}
