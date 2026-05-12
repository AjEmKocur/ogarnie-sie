<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/ogarnie-sie-logo.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-950 font-sans antialiased text-slate-100">
        <div class="flex min-h-screen flex-col">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-gray-200 bg-slate-950/70 shadow">
                    <div class="mx-auto max-w-7xl px-5 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="og-footer border-t border-gray-200">
                <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-3 px-5 py-6 text-sm text-slate-400 sm:flex-row sm:items-center sm:px-6 lg:px-8">
                    <p>&copy; {{ date('Y') }} Ogarnie się. Serwis komputerowy.</p>
                    <p>Godziny pracy: Pn-Pt 9:00-18:00</p>
                </div>
            </footer>
        </div>

        <div id="confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 px-4">
            <div class="rounded-2xl border border-blue-300/30 bg-slate-900 p-6 shadow-2xl shadow-blue-900/30" style="width:min(92vw,540px);">
                <p id="confirm-modal-title" class="text-lg font-semibold text-slate-100">Potwierdzenie</p>
                <p id="confirm-modal-message" class="mt-2 text-sm text-slate-300">Czy na pewno chcesz wykonać tę akcję?</p>
                <div class="mt-6 flex justify-end gap-2">
                    <button
                        id="confirm-modal-cancel"
                        type="button"
                        class="inline-flex items-center rounded-md border border-slate-500/60 bg-slate-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-200 transition hover:bg-slate-700"
                    >
                        Anuluj
                    </button>
                    <button
                        id="confirm-modal-confirm"
                        type="button"
                        class="inline-flex items-center rounded-md border border-blue-400/60 bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-blue-500"
                    >
                        Potwierdź
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const roots = document.querySelectorAll('[data-ticket-notifications-root]');
                if (!roots.length) {
                    return;
                }

                const itemClass = 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-200 hover:bg-slate-800 focus:outline-none focus:bg-slate-800 transition duration-150 ease-in-out';

                const renderRoot = (root, payload) => {
                    const bell = root.querySelector('[data-ticket-bell]');
                    const badge = root.querySelector('[data-ticket-notifications-badge]');
                    const list = root.querySelector('[data-ticket-notifications-list]');
                    if (!bell || !badge || !list) return;

                    const total = Number(payload?.total || 0);
                    if (total > 0) {
                        bell.classList.add('text-white', 'drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]');
                        bell.classList.remove('text-slate-400');
                        badge.textContent = String(Math.min(99, total));
                        badge.classList.remove('hidden');
                        badge.classList.add('inline-flex');
                    } else {
                        bell.classList.remove('text-white', 'drop-shadow-[0_0_8px_rgba(255,255,255,0.35)]');
                        bell.classList.add('text-slate-400');
                        badge.textContent = '0';
                        badge.classList.add('hidden');
                        badge.classList.remove('inline-flex');
                    }

                    list.innerHTML = '';
                    const items = Array.isArray(payload?.items) ? payload.items : [];
                    if (!items.length) {
                        const empty = document.createElement('div');
                        empty.className = 'px-4 py-2 text-sm text-slate-400';
                        empty.textContent = 'Brak nowych powiadomień.';
                        list.appendChild(empty);
                        return;
                    }

                    items.forEach((item) => {
                        const link = document.createElement('a');
                        link.href = item.url || '#';
                        link.className = itemClass;

                        const wrap = document.createElement('div');
                        wrap.className = 'flex flex-col gap-1';

                        const title = document.createElement('span');
                        title.className = 'font-semibold';
                        title.textContent = item.title || 'Powiadomienie';
                        wrap.appendChild(title);

                        if (item.time) {
                            const time = document.createElement('span');
                            time.className = 'text-xs text-slate-400';
                            time.textContent = item.time;
                            wrap.appendChild(time);
                        }

                        link.appendChild(wrap);
                        list.appendChild(link);
                    });
                };

                const refreshRoot = async (root) => {
                    const url = root.getAttribute('data-url');
                    if (!url) return;

                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        if (!response.ok) return;
                        const data = await response.json();
                        renderRoot(root, data);
                    } catch (error) {
                        // Cicho pomijamy chwilowe błędy sieci.
                    }
                };

                const refreshAll = () => roots.forEach((root) => void refreshRoot(root));

                setInterval(refreshAll, 15000);
                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) {
                        refreshAll();
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('confirm-modal');
                const titleEl = document.getElementById('confirm-modal-title');
                const messageEl = document.getElementById('confirm-modal-message');
                const cancelBtn = document.getElementById('confirm-modal-cancel');
                const confirmBtn = document.getElementById('confirm-modal-confirm');

                let pendingForm = null;

                if (!modal || !titleEl || !messageEl || !cancelBtn || !confirmBtn) {
                    return;
                }

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    pendingForm = null;
                };

                const openModal = (form) => {
                    const title = form.dataset.confirmTitle || 'Potwierdzenie';
                    const message = form.dataset.confirmMessage || 'Czy na pewno chcesz wykonać tę akcję?';

                    titleEl.textContent = title;
                    messageEl.textContent = message;
                    pendingForm = form;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                };

                document.querySelectorAll('form[data-confirm-message]').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();
                        openModal(form);
                    });
                });

                cancelBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                confirmBtn.addEventListener('click', () => {
                    if (!pendingForm) return;
                    const formToSubmit = pendingForm;
                    closeModal();
                    formToSubmit.submit();
                });
            });
        </script>
    </body>
</html>
