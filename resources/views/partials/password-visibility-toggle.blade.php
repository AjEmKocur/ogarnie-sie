@php
    $targetId = $inputId ?? null;
@endphp

@if ($targetId)
    <button
        type="button"
        data-password-toggle="{{ $targetId }}"
        class="z-10 text-slate-400 transition hover:text-slate-100 focus:outline-none"
        style="position:absolute !important; right:12px; top:20%; width:20px; height:20px; display:flex !important; align-items:center; justify-content:center; border:0; padding:0; margin:0; background:transparent; box-shadow:none; outline:none; line-height:1;"
        aria-label="Pokaż hasło"
        title="Pokaż hasło"
    >
        <span class="relative block h-4 w-4">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="pointer-events-none block h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-7 11-7s11 7 11 7s-4 7-11 7s-11-7-11-7Z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            <svg data-eye-slash aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="pointer-events-none hidden absolute inset-0 h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 20L20 4"/>
            </svg>
        </span>
    </button>

    <script>
        (function () {
            const btn = document.querySelector('[data-password-toggle="{{ $targetId }}"]');
            const input = document.getElementById('{{ $targetId }}');

            if (!btn || !input) return;

            let toggled = false;
            let pressed = false;
            const eyeSlash = btn.querySelector('[data-eye-slash]');

            const render = () => {
                const visible = toggled || pressed;
                input.type = visible ? 'text' : 'password';
                btn.setAttribute('aria-label', visible ? 'Ukryj hasło' : 'Pokaż hasło');
                btn.setAttribute('title', visible ? 'Ukryj hasło' : 'Pokaż hasło');
                eyeSlash?.classList.toggle('hidden', !visible);
            };

            btn.addEventListener('click', () => {
                toggled = !toggled;
                render();
            });

            const startPress = (event) => {
                if (event.type === 'mousedown' && event.button !== 0) return;
                pressed = true;
                render();
            };

            const endPress = () => {
                pressed = false;
                render();
            };

            btn.addEventListener('mousedown', startPress);
            btn.addEventListener('touchstart', startPress, { passive: true });
            btn.addEventListener('mouseup', endPress);
            btn.addEventListener('mouseleave', endPress);
            btn.addEventListener('touchend', endPress);
            btn.addEventListener('touchcancel', endPress);

            render();
        })();
    </script>
@endif
