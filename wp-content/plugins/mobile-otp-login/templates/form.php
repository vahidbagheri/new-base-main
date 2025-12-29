<?php
/**
 * Form Template (App-like UI)
 *
 * - ارسال مجدد به شکل لینک مینیمال کنار پیام موفقیت
 * - تغییر شماره به شکل چیپ/آیکن کوچک (بدون فضای اضافه)
 *
 * @package MOTP
 */

if (!defined('ABSPATH')) exit;
?>

<div class="motp" dir="rtl">
  <button class="motp__trigger" id="motp-open" type="button" aria-haspopup="dialog">
    ورود / ثبت‌نام
  </button>

  <div class="motp__backdrop" id="motp-backdrop" style="display:none;"></div>

  <div class="motp__modal" id="motp-modal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="motp-title">
    <div class="motp__card" role="document">
      <div class="motp__head">
        <div class="motp__title" id="motp-title">ورود / ثبت‌نام</div>
        <button class="motp__close" id="motp-close" type="button" aria-label="بستن">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="motp__body">

        <!-- Message (app-like) -->
        <div id="motp-msg" class="motp__msg" style="display:none;">
          <div class="motp__msg-text" id="motp-msg-text"></div>

          <!-- resend link shown only in step 2 -->
          <button class="motp__msg-link" id="motp-resend" type="button" disabled style="display:none;">
            ارسال مجدد <span class="motp__timer" id="motp-timer">(60)</span>
            <span class="motp__loader motp__loader--dark" aria-hidden="true"></span>
          </button>
        </div>

        <input type="hidden" id="motp-redirect" value="<?php echo esc_attr( home_url( add_query_arg([]) ) ); ?>">

        <!-- Step 1 -->
        <div class="motp__step" id="motp-step-1">
          <label class="motp__label" for="motp-mobile">شماره موبایل</label>
          <div class="motp__row">
            <input class="motp__input" id="motp-mobile" type="tel" inputmode="numeric" autocomplete="tel"
                   placeholder="09123456789" aria-describedby="motp-help">

            <button class="motp__btn motp__btn--primary" id="motp-send" type="button">
              <span class="motp__btn-text">ارسال کد</span>
              <span class="motp__loader" aria-hidden="true"></span>
            </button>
          </div>
          <div class="motp__help" id="motp-help">کد تایید به همین شماره پیامک می‌شود.</div>
        </div>

        <!-- Step 2 -->
        <div class="motp__step" id="motp-step-2" style="display:none;">
          <div class="motp__label-row">
            <label class="motp__label" for="motp-otp">کد تایید</label>

            <button class="motp__icon-chip" id="motp-edit" type="button" aria-label="تغییر شماره">
              <span class="motp__icon" aria-hidden="true">✎</span>
              <span class="motp__icon-chip-text">تغییر شماره</span>
            </button>
          </div>

          <div class="motp__row">
            <input class="motp__input" id="motp-otp" type="tel" inputmode="numeric" autocomplete="one-time-code"
                   placeholder="******">

            <button class="motp__btn motp__btn--primary" id="motp-verify" type="button">
              <span class="motp__btn-text">تایید</span>
              <span class="motp__loader" aria-hidden="true"></span>
            </button>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="motp__step motp__success" id="motp-step-3" style="display:none;">
          <div class="motp__success-icon">✅</div>
          <div class="motp__success-title">شما وارد شدید</div>
          <div class="motp__success-sub">در حال به‌روزرسانی صفحه…</div>

          <div class="motp__success-loader" aria-hidden="true">
            <span class="motp__dot"></span><span class="motp__dot"></span><span class="motp__dot"></span>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
