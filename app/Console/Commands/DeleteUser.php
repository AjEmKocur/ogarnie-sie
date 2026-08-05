<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUser extends Command
{
    protected $signature = 'users:delete
        {email : Adres e-mail uzytkownika do usuniecia}
        {--force : Nie pytaj o potwierdzenie}';

    protected $description = 'Usuwa uzytkownika po adresie e-mail.';

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
            $this->warn("Nie znaleziono uzytkownika z adresem {$email}.");

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Usunac uzytkownika {$email}?")) {
            $this->warn('Przerwano bez zmian.');

            return self::SUCCESS;
        }

        $user->delete();

        $this->info("Usunieto uzytkownika {$email}.");

        return self::SUCCESS;
    }
}
