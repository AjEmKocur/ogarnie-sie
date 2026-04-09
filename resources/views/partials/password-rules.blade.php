@props([
    'passwordId',
    'confirmationId' => null,
])

<div
    class="fixed z-[90] hidden w-[300px] rounded-lg border border-slate-700 bg-slate-900/95 p-3 text-xs shadow-xl shadow-slate-950/50 backdrop-blur"
    data-password-rules
    data-password-id="{{ $passwordId }}"
    @if ($confirmationId) data-confirmation-id="{{ $confirmationId }}" @endif
>
    <p class="mb-2 font-semibold text-slate-200">Wymagania hasła:</p>
    <ul class="space-y-1 text-slate-300">
        <li data-rule="min" class="flex items-center gap-2">
            <span data-icon class="font-bold text-rose-500">&#10007;</span>
            <span>minimum 8 znaków</span>
        </li>
        <li data-rule="upper" class="flex items-center gap-2">
            <span data-icon class="font-bold text-rose-500">&#10007;</span>
            <span>co najmniej 1 duża litera</span>
        </li>
        <li data-rule="digit" class="flex items-center gap-2">
            <span data-icon class="font-bold text-rose-500">&#10007;</span>
            <span>co najmniej 1 cyfra</span>
        </li>
        @if ($confirmationId)
            <li data-rule="match" class="flex items-center gap-2">
                <span data-icon class="font-bold text-rose-500">&#10007;</span>
            <span>hasła są takie same</span>
            </li>
        @endif
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const blocks = document.querySelectorAll('[data-password-rules]');

        const positionBlock = (block, targetInput) => {
            if (!targetInput) return;

            const rect = targetInput.getBoundingClientRect();
            const panelWidth = block.offsetWidth || 300;
            const spacing = 12;

            let left = rect.right + spacing;
            let top = rect.top + window.scrollY;

            if (left + panelWidth > window.innerWidth - 12) {
                left = rect.left - panelWidth - spacing;
            }

            if (left < 12) {
                left = Math.max(12, window.innerWidth - panelWidth - 12);
            }

            if (top + block.offsetHeight > window.scrollY + window.innerHeight - 12) {
                top = window.scrollY + window.innerHeight - block.offsetHeight - 12;
            }

            if (top < window.scrollY + 12) {
                top = window.scrollY + 12;
            }

            block.style.left = `${left}px`;
            block.style.top = `${top}px`;
        };

        blocks.forEach((block) => {
            if (block.dataset.initialized === '1') {
                return;
            }
            block.dataset.initialized = '1';

            const passwordInput = document.getElementById(block.dataset.passwordId);
            const confirmationId = block.dataset.confirmationId;
            const confirmationInput = confirmationId ? document.getElementById(confirmationId) : null;

            if (!passwordInput) {
                return;
            }

            const isRelatedTarget = (target) => {
                return target === passwordInput || target === confirmationInput;
            };

            const setRuleState = (ruleName, ok) => {
                const row = block.querySelector(`[data-rule="${ruleName}"]`);
                if (!row) return;

                const icon = row.querySelector('[data-icon]');
                if (!icon) return;

                icon.textContent = ok ? '\u2713' : '\u2717';
                icon.classList.toggle('text-emerald-400', ok);
                icon.classList.toggle('text-rose-500', !ok);
            };

            const validate = () => {
                const value = passwordInput.value || '';
                setRuleState('min', value.length >= 8);
                setRuleState('upper', /[A-Z]/.test(value));
                setRuleState('digit', /\d/.test(value));

                if (confirmationInput) {
                    setRuleState('match', value.length > 0 && confirmationInput.value === value);
                }
            };

            const show = (targetInput) => {
                block.classList.remove('hidden');
                positionBlock(block, targetInput);
            };

            const hide = () => {
                block.classList.add('hidden');
            };

            passwordInput.addEventListener('input', validate);
            if (confirmationInput) {
                confirmationInput.addEventListener('input', validate);
            }

            document.addEventListener('focusin', (event) => {
                const target = event.target;
                if (isRelatedTarget(target)) {
                    show(target);
                } else {
                    hide();
                }
            });

            window.addEventListener('scroll', () => {
                const active = document.activeElement;
                if (isRelatedTarget(active)) {
                    positionBlock(block, active);
                }
            }, { passive: true });

            window.addEventListener('resize', () => {
                const active = document.activeElement;
                if (isRelatedTarget(active)) {
                    positionBlock(block, active);
                }
            });

            validate();
        });
    });
</script>
