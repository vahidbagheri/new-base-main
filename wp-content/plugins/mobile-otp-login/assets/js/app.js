/**
 * MOTP Frontend JS (App-like UI)
 *
 * - Resend as minimal link inside success message (step 2)
 * - Change phone as small icon chip
 * - Step-based UI, Enter triggers, loaders, countdown
 */

(function () {
  const qs = (s) => document.querySelector(s);
  const onlyDigits = (s) => (s || "").replace(/\D+/g, "");

  function show(el) { if (el) el.style.display = ""; }
  function hide(el) { if (el) el.style.display = "none"; }

  function setResendVisible(visible) {
    const btn = qs("#motp-resend");
    if (!btn) return;
    btn.style.display = visible ? "" : "none";
  }

  function step(n) {
    const s1 = qs("#motp-step-1");
    const s2 = qs("#motp-step-2");
    const s3 = qs("#motp-step-3");
    if (!s1 || !s2 || !s3) return;

    hide(s1); hide(s2); hide(s3);

    if (n === 1) show(s1);
    if (n === 2) show(s2);
    if (n === 3) show(s3);

    // Resend link is only meaningful in step 2
    setResendVisible(n === 2);
  }

  function setMsg(msg, ok) {
    const box = qs("#motp-msg");
    const text = qs("#motp-msg-text");
    if (!box || !text) return;

    box.style.display = msg ? "" : "none";
    text.textContent = msg || "";
    box.dataset.ok = ok ? "1" : "0";
  }

  async function post(action, data) {
    const params = new URLSearchParams();
    params.append("action", action);
    params.append("nonce", MOTP.nonce);
    Object.keys(data || {}).forEach((k) => params.append(k, data[k]));

    const res = await fetch(MOTP.ajaxurl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
      body: params.toString(),
      credentials: "same-origin",
    });

    return await res.json();
  }

  function setLoading(btn, loading) {
    if (!btn) return;
    btn.classList.toggle("is-loading", !!loading);
    if (btn.id !== "motp-resend") btn.disabled = !!loading;
  }

  // Countdown
  let resendLeft = 0;
  let resendTimerId = null;

  function setResendEnabled(enabled) {
    const btn = qs("#motp-resend");
    if (!btn) return;
    btn.disabled = !enabled;
  }

  function renderTimer() {
    const t = qs("#motp-timer");
    if (!t) return;
    t.textContent = `(${resendLeft})`;
  }

  function startResendCountdown(seconds) {
    resendLeft = Math.max(0, Number(seconds || 0));
    if (resendTimerId) clearInterval(resendTimerId);

    setResendEnabled(resendLeft <= 0);
    renderTimer();

    if (resendLeft <= 0) return;

    resendTimerId = setInterval(() => {
      resendLeft -= 1;
      if (resendLeft <= 0) {
        resendLeft = 0;
        clearInterval(resendTimerId);
        resendTimerId = null;
        setResendEnabled(true);
      } else {
        setResendEnabled(false);
      }
      renderTimer();
    }, 1000);
  }

  function openModal() {
    setMsg("", true);
    step(1);

    show(qs("#motp-backdrop"));
    show(qs("#motp-modal"));
    document.body.classList.add("motp--lock");
    setTimeout(() => qs("#motp-mobile")?.focus(), 0);

    // Keep resend disabled initially; will start after sending OTP
    startResendCountdown(60);
    setResendVisible(false);
  }

  function closeModal() {
    hide(qs("#motp-backdrop"));
    hide(qs("#motp-modal"));
    document.body.classList.remove("motp--lock");
  }

  document.addEventListener("click", async (e) => {
    if (e.target?.id === "motp-open") {
      e.preventDefault();
      openModal();
      return;
    }

    if (e.target?.id === "motp-close" || e.target?.closest?.("#motp-close") || e.target?.id === "motp-backdrop") {
      e.preventDefault();
      closeModal();
      return;
    }

    // Change phone -> step 1
    if (e.target?.id === "motp-edit" || e.target?.closest?.("#motp-edit")) {
      e.preventDefault();
      setMsg("", true);
      step(1);
      setResendVisible(false);
      setTimeout(() => qs("#motp-mobile")?.focus(), 0);
      return;
    }

    // Send OTP
    if (e.target?.id === "motp-send" || e.target?.closest?.("#motp-send")) {
      e.preventDefault();
      setMsg("", true);

      const btn = qs("#motp-send");
      const mobile = onlyDigits(qs("#motp-mobile")?.value);
      if (!mobile) return setMsg("شماره موبایل را وارد کنید.", false);

      setLoading(btn, true);
      try {
        const json = await post("motp_send_otp", { mobile });
        if (json.success) {
          setMsg(json.data?.message || "کد ارسال شد.", true);
          step(2);
          setTimeout(() => qs("#motp-otp")?.focus(), 0);
          startResendCountdown(60);
          setResendVisible(true);
        } else {
          setMsg(json.data?.message || "خطا در ارسال کد.", false);
        }
      } catch (err) {
        setMsg("خطای ارتباط. دوباره تلاش کنید.", false);
      } finally {
        setLoading(btn, false);
      }
      return;
    }

    // Resend (inside message)
    if (e.target?.id === "motp-resend" || e.target?.closest?.("#motp-resend")) {
      e.preventDefault();
      const btn = qs("#motp-resend");
      const mobile = onlyDigits(qs("#motp-mobile")?.value);
      if (!mobile) return setMsg("شماره موبایل را وارد کنید.", false);
      if (btn.disabled) return;

      btn.classList.add("is-loading");
      try {
        const json = await post("motp_send_otp", { mobile });
        if (json.success) {
          setMsg("کد مجدداً ارسال شد.", true);
          startResendCountdown(60);
        } else {
          setMsg(json.data?.message || "خطا در ارسال مجدد.", false);
        }
      } catch (err) {
        setMsg("خطای ارتباط. دوباره تلاش کنید.", false);
      } finally {
        btn.classList.remove("is-loading");
        btn.disabled = resendLeft > 0;
      }
      return;
    }

    // Verify
    if (e.target?.id === "motp-verify" || e.target?.closest?.("#motp-verify")) {
      e.preventDefault();
      setMsg("", true);

      const btn = qs("#motp-verify");
      const mobile = onlyDigits(qs("#motp-mobile")?.value);
      const otp = onlyDigits(qs("#motp-otp")?.value);
      const redirect = qs("#motp-redirect")?.value || "";

      if (!mobile) return setMsg("شماره موبایل نامعتبر است.", false);
      if (!otp || otp.length < 4) return setMsg("کد را درست وارد کنید.", false);

      setLoading(btn, true);
      try {
        const json = await post("motp_verify_otp", { mobile, otp, redirect });
        if (json.success) {
          setMsg("", true);
          step(3);
          setTimeout(() => window.location.reload(), 700);
        } else {
          setMsg(json.data?.message || "کد اشتباه است.", false);
        }
      } catch (err) {
        setMsg("خطای ارتباط. دوباره تلاش کنید.", false);
      } finally {
        setLoading(btn, false);
      }
      return;
    }
  });

  document.addEventListener("keydown", (e) => {
    const modal = qs("#motp-modal");
    const visible = modal && modal.style.display !== "none";
    if (!visible) return;

    if (e.key === "Escape") {
      closeModal();
      return;
    }

    if (e.key === "Enter") {
      const active = document.activeElement;
      if (!active) return;

      if (active.id === "motp-mobile") {
        e.preventDefault();
        qs("#motp-send")?.click();
      } else if (active.id === "motp-otp") {
        e.preventDefault();
        qs("#motp-verify")?.click();
      }
    }
  });
})();
