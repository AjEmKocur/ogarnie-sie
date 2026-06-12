<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tickets')
            ->where('status', Ticket::STATUS_CANCELLED)
            ->update([
                'payment_mode' => Ticket::PAYMENT_MODE_NONE,
                'payment_status' => Ticket::PAYMENT_STATUS_NOT_REQUIRED,
                'payment_amount' => null,
                'payment_requested_at' => null,
                'paid_at' => null,
            ]);
    }

    public function down(): void
    {
    }
};

