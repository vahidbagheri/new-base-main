<?php
/**
 * SMS Sender (VoiceWeb / Mehrafraz)
 *
 * نکته امنیتی:
 * بهتره یوزرنیم/پسورد داخل کد هاردکد نشه. اما اگر فعلاً باید باشه،
 * حداقل با constant قابل override کردیم.
 *
 * @param string  $message
 * @param string  $mobile_number
 * @param boolean $for_customer
 *
 * @return string|false API response string, or false on failure.
 */
function voiceweb_send_sms($message, $mobile_number, $for_customer = false) {
    $url = 'https://mehrafraz.com:443/fullrest/api/Send';

    // Allow override via wp-config.php or other place
    $user = defined('MOTP_SMS_USERNAME') ? MOTP_SMS_USERNAME : 'citynet';
    $pass = defined('MOTP_SMS_PASSWORD') ? MOTP_SMS_PASSWORD : 'Citynet#8847';
    $domain = defined('MOTP_SMS_DOMAIN') ? MOTP_SMS_DOMAIN : 'agency';

    $data = [
        'Smsbody'    => (string) $message,
        'Mobiles'    => [(string) $mobile_number],
        'Id'         => '0',
        'UserName'   => $user,
        'Password'   => $pass,
        'DomainName' => $domain,
    ];

    $jsonData = wp_json_encode($data);
    if (!$jsonData) {
        return false;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
