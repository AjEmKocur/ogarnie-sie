<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-slate-100">Zespół operatorów</h2>
            <span class="rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-200">Dostęp tylko dla głównego admina</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @include('admin.partials.breadcrumbs', [
                'items' => [
                    ['label' => 'Strona główna', 'url' => route('admin.dashboard')],
                    ['label' => 'Operatorzy'],
                ],
            ])

            <div class="rounded-xl border border-blue-400/30 bg-slate-900/70 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-100">Dodaj konto operatora</h3>
                <p class="mt-1 text-sm text-slate-300">Operator obsługuje stronę i zgłoszenia zgodnie z przydzielonymi uprawnieniami.</p>

                <form method="POST" action="{{ route('admin.team.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
                    @csrf
                    <div>
                        <label for="name" class="text-sm font-medium text-slate-200">Imię i nazwisko</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" class="mt-1 w-full rounded-md border-blue-400/30 bg-slate-800/70 text-slate-100 shadow-sm focus:border-blue-400 focus:ring-blue-400">
                    </div>
                    <div>
                        <label for="username" class="text-sm font-medium text-slate-200">Login operatora</label>
                        <input id="username" name="username" type="text" required value="{{ old('username') }}" class="mt-1 w-full rounded-md border-blue-400/30 bg-slate-800/70 text-slate-100 shadow-sm focus:border-blue-400 focus:ring-blue-400" placeholder="np. operator.jan">
                    </div>
                    <div>
                        <label for="email" class="text-sm font-medium text-slate-200">E-mail</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 w-full rounded-md border-blue-400/30 bg-slate-800/70 text-slate-100 shadow-sm focus:border-blue-400 focus:ring-blue-400">
                    </div>
                    <div>
                        <label for="password" class="text-sm font-medium text-slate-200">Hasło startowe</label>
                        <input id="password" name="password" type="text" required class="mt-1 w-full rounded-md border-blue-400/30 bg-slate-800/70 text-slate-100 shadow-sm focus:border-blue-400 focus:ring-blue-400">
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-slate-200">Checklista dostępu operatora</p>
                        <div class="mt-2 grid gap-2 md:grid-cols-2">
                            @foreach ($permissionOptions as $permissionKey => $permissionLabel)
                                <label class="flex items-center gap-2 rounded-md border border-blue-400/30 bg-slate-800/40 px-3 py-2 text-sm text-slate-200">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permissionKey }}"
                                        @checked(in_array($permissionKey, old('permissions', ['tickets', 'cms_services']), true))
                                        class="rounded border-blue-400/40 bg-slate-900 text-blue-500 shadow-sm focus:ring-blue-400"
                                    >
                                    <span>{{ $permissionLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-400">Utwórz operatora</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-blue-400/30 bg-slate-900/70 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-100">Operatorzy</h3>

                <div class="mt-4 space-y-4">
                    @forelse ($operators as $operator)
                        <div class="rounded-lg border border-blue-400/30 bg-slate-900/50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-100">{{ $operator->name }}</p>
                                    <p class="text-sm text-slate-300">Login: {{ $operator->username ?? '-' }}</p>
                                    <p class="text-sm text-slate-300">{{ $operator->email }}</p>
                                    <p class="mt-1 text-xs {{ $operator->is_active ? 'text-green-300' : 'text-red-300' }}">
                                        {{ $operator->is_active ? 'Aktywny' : 'Wyłączony' }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.team.toggle', $operator) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-md border border-blue-400/40 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-100 hover:bg-slate-800">
                                        {{ $operator->is_active ? 'Wyłącz' : 'Aktywuj' }}
                                    </button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.team.permissions', $operator) }}" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <div class="grid gap-2 md:grid-cols-2">
                                    @foreach ($permissionOptions as $permissionKey => $permissionLabel)
                                        <label class="flex items-center gap-2 rounded-md border border-blue-400/30 bg-slate-800/40 px-3 py-2 text-sm text-slate-200">
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permissionKey }}"
                                                @checked(in_array($permissionKey, $operator->admin_permissions ?? [], true))
                                                class="rounded border-blue-400/40 bg-slate-900 text-blue-500 shadow-sm focus:ring-blue-400"
                                            >
                                            <span>{{ $permissionLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="rounded-md bg-slate-700 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-slate-600">
                                        Zapisz uprawnienia
                                    </button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('admin.team.reset-password', $operator) }}" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <label for="reset_password_{{ $operator->id }}" class="text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Reset hasła operatora (ustawia nowe hasło startowe)
                                </label>
                                <div class="mt-2 flex flex-col gap-2 md:flex-row">
                                    <input
                                        id="reset_password_{{ $operator->id }}"
                                        name="new_password"
                                        type="text"
                                        required
                                        minlength="8"
                                        placeholder="Nowe hasło startowe"
                                        class="w-full rounded-md border-blue-400/30 bg-slate-800/70 text-slate-100 shadow-sm focus:border-blue-400 focus:ring-blue-400"
                                    >
                                    <button type="submit" class="rounded-md bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-blue-400">
                                        Zresetuj hasło
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <p class="rounded-md border border-dashed border-blue-400/40 px-4 py-3 text-sm text-slate-300">Brak operatorów. Utwórz pierwsze konto powyżej.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
