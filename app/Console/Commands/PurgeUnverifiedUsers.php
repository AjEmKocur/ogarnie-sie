<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class PurgeUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:purge-unverified {--days=7 : Usuń konta starsze niż X dni} {--dry-run : Tylko pokaż, co byłoby usunięte}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Usuwa niezweryfikowane konta użytkowników po określonym czasie.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $threshold = Carbon::now()->subDays($days);

        $query = User::query()
            ->whereNull('email_verified_at')
            ->whereNotIn('role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
            ->where('created_at', '<', $threshold)
            ->whereDoesntHave('tickets')
            ->whereDoesntHave('attachments')
            ->whereDoesntHave('ticketMessages')
            ->whereDoesntHave('testimonials');

        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info("Brak kont do usunięcia (próg: {$days} dni).");
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn("Tryb podglądu: do usunięcia {$count} kont (próg: {$days} dni).");
            return self::SUCCESS;
        }

        $deleted = $query->delete();
        $this->info("Usunięto {$deleted} niezweryfikowanych kont (próg: {$days} dni).");

        return self::SUCCESS;
    }
}
