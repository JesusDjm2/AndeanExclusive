// Anti-spam captcha for the "Request Info" booking form
// (resources/views/layouts/partials/public-booking-form.blade.php).
// Replaces the identical inline <script> that used to be duplicated
// verbatim in booking.blade.php and booking-castellano.blade.php.

function randomSum() {
    const num1 = Math.floor(Math.random() * 12) + 3;
    const num2 = Math.floor(Math.random() * (15 - num1)) + num1;
    return { num1, num2, sum: num1 + num2 };
}

function setupCaptcha(captcha) {
    const form = captcha.closest('form');
    const checkmark = form.querySelector('[data-captcha-checkmark]');
    const num1El = captcha.querySelector('[data-captcha-num1]');
    const num2El = captcha.querySelector('[data-captcha-num2]');
    const answerEl = captcha.querySelector('[data-captcha-answer]');
    const correctEl = captcha.querySelector('[data-captcha-correct]');
    const verifyBtn = captcha.querySelector('[data-captcha-verify]');
    const submitBtn = form.querySelector('[data-captcha-submit]');
    let attempts = 0;

    const roll = () => {
        const { num1, num2, sum } = randomSum();
        num1El.textContent = num1;
        num2El.textContent = num2;
        correctEl.value = sum;
    };

    roll();

    verifyBtn.addEventListener('click', () => {
        const given = parseInt(answerEl.value, 10);
        const correct = parseInt(correctEl.value, 10);

        if (given === correct) {
            captcha.classList.add('is-solved');
            submitBtn.disabled = false;
            checkmark.classList.add('is-visible');
            // Draw the eye to the now-active submit button.
            submitBtn.classList.add('just-enabled');
            setTimeout(() => submitBtn.classList.remove('just-enabled'), 900);
            setTimeout(() => checkmark.classList.remove('is-visible'), 1200);
            return;
        }

        attempts += 1;
        if (attempts >= 3) {
            window.location.href = '404';
            return;
        }
        roll();
    });
}

export function initCaptcha() {
    document.querySelectorAll('[data-captcha]').forEach(setupCaptcha);
}
