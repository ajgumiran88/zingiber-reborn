/**
 * Air Warfare Center Hub — "Got Insights or Ideas?" Form Engine
 * Handles real-time validation, transmission telemetry, and interactive UX.
 */

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('awcInsightsForm');
    const formView = document.getElementById('awcFormView');
    const successHud = document.getElementById('awcSuccessHud');
    const submitBtn = document.getElementById('awcSubmitBtn');
    const resetBtn = document.getElementById('awcResetBtn');
    const closeBtn = document.getElementById('awcCloseBtn');
    const charCounter = document.getElementById('awcCharCounter');
    const commentsInput = document.getElementById('awcComments');
    const contactInput = document.getElementById('awcContact');
    const refCodeEl = document.getElementById('awcRefCode');
    const timestampEl = document.getElementById('awcTimestamp');

    // 1. Live character counter for Comments
    if (commentsInput && charCounter) {
        commentsInput.addEventListener('input', () => {
            const count = commentsInput.value.length;
            charCounter.textContent = `${count} / 2000`;
            if (count > 1900) {
                charCounter.style.color = 'var(--awc-danger)';
            } else {
                charCounter.style.color = 'var(--awc-text-muted)';
            }
        });
    }

    // 2. Phone input auto-formatter
    if (contactInput) {
        contactInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.startsWith('63')) {
                // Philippine format +63 9XX XXX XXXX
                if (val.length > 2) val = '+63 ' + val.substring(2);
                if (val.length > 8) val = val.substring(0, 7) + ' ' + val.substring(7);
                if (val.length > 12) val = val.substring(0, 11) + ' ' + val.substring(11, 15);
            } else if (val.startsWith('09')) {
                // Local mobile 09XX XXX XXXX
                if (val.length > 4) val = val.substring(0, 4) + ' ' + val.substring(4);
                if (val.length > 8) val = val.substring(0, 8) + ' ' + val.substring(8, 12);
            }
            e.target.value = val;
        });
    }

    // Helper: Field validation
    const validateField = (fieldId, validator) => {
        const group = document.getElementById(`group-${fieldId}`);
        const input = document.getElementById(fieldId);
        if (!input || !group) return true;

        const isValid = validator(input.value.trim());
        if (!isValid) {
            group.classList.add('is-invalid');
        } else {
            group.classList.remove('is-invalid');
        }
        return isValid;
    };

    // Live validation clearance on input
    ['awcEmail', 'awcFirstName', 'awcLastName', 'awcContact', 'awcComments'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => {
                const group = document.getElementById(`group-${id}`);
                if (group) group.classList.remove('is-invalid');
            });
        }
    });

    // 3. Form Submit Handler
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Run Validations
            const isEmailValid = validateField('awcEmail', val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val));
            const isFirstNameValid = validateField('awcFirstName', val => val.length >= 2);
            const isLastNameValid = validateField('awcLastName', val => val.length >= 2);
            const isContactValid = validateField('awcContact', val => val.length >= 7);
            const isCommentsValid = validateField('awcComments', val => val.length >= 10);

            if (!isEmailValid || !isFirstNameValid || !isLastNameValid || !isContactValid || !isCommentsValid) {
                // Focus first invalid field
                const firstInvalid = form.querySelector('.is-invalid input, .is-invalid textarea');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            // Enter Loading Transmission State
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');

            const payload = {
                category: document.getElementById('awcCategory')?.value || 'Insight / General Feedback',
                email: document.getElementById('awcEmail')?.value.trim(),
                firstName: document.getElementById('awcFirstName')?.value.trim(),
                lastName: document.getElementById('awcLastName')?.value.trim(),
                contact: document.getElementById('awcContact')?.value.trim(),
                comments: document.getElementById('awcComments')?.value.trim(),
                submittedAt: new Date().toISOString()
            };

            // Store in local backup
            try {
                const existing = JSON.parse(localStorage.getItem('awc_insights_log') || '[]');
                existing.push(payload);
                localStorage.setItem('awc_insights_log', JSON.stringify(existing));
            } catch (err) {
                // ignore storage failures in private windows
            }

            // Simulate Network Telemetry Transmission
            setTimeout(() => {
                submitBtn.classList.remove('is-loading');
                submitBtn.disabled = false;

                // Generate Reference Code (e.g. AWC-2026-8941)
                const randomId = Math.floor(1000 + Math.random() * 9000);
                const refCode = `AWC-${new Date().getFullYear()}-${randomId}`;
                if (refCodeEl) refCodeEl.textContent = refCode;
                if (timestampEl) timestampEl.textContent = new Date().toLocaleTimeString();

                // Switch to Success HUD
                formView.style.display = 'none';
                successHud.classList.add('is-active');
            }, 1200);
        });
    }

    // 4. Reset / Send Another Button
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            form.reset();
            if (charCounter) charCounter.textContent = '0 / 2000';
            successHud.classList.remove('is-active');
            formView.style.display = 'block';
        });
    }

    // 5. Close Button Action
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            // Visual feedback for close or hide modal
            const overlay = document.querySelector('.awc-modal-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.transform = 'scale(0.96)';
                overlay.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            }
        });
    }

    // 6. Escape key listener to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && closeBtn) {
            closeBtn.click();
        }
    });
});
