<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    protected $signature = 'users:make-admin
        {email : Adres e-mail istniejacego uzytkownika}
        {--verify : Oznacz adres e-mail jako zweryfikowany}
        {--force : Nie pytaj o potwierdzenie}';

    protected $description = 'Nadaje istniejacemu uzytkownikowi role glownego administratora.';

    public function handle(): int
    {
        $email = mb_strtolower(trim((string) $this->argument('email')));

        if ($email === '') {
            $this->error('Podaj adres e-mail uzytkownika.');

            return self::FAILURE;
        }

        $user = User::query()
            ->where('email', $email)
            ->first();

        if (! $user) {
            $this->error("Nie znaleziono uzytkownika z adresem {$email}.");
            $this->line('Najpierw zaloz konto przez formularz rejestracji na stronie.');

            return self::FAILURE;
        }

        if (! $this->option('verify') && ! $user->hasVerifiedEmail()) {
            $this->error('Konto nie ma zweryfikowanego adresu e-mail.');
            $this->line('Zweryfikuj e-mail przez link z wiadomosci albo uruchom komende z opcja --verify.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm("Nadac role glownego admina dla {$email}?")) {
            $this->warn('Przerwano bez zmian.');

            return self::SUCCESS;
        }

        $user->forceFill([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'admin_permissions' => null,
            'force_password_change' => false,
            'email_verified_at' => $this->option('verify') && ! $user->hasVerifiedEmail()
                ? now()
                : $user->email_verified_at,
        ])->save();

        $this->info("Uzytkownik {$email} jest teraz glownym administratorem.");

        return self::SUCCESS;
    }
}
