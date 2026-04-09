<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->string('payment_mode')
                ->default(Ticket::PAYMENT_MODE_NONE)
                ->after('admin_note');
            $table->decimal('payment_amount', 10, 2)
                ->nullable()
                ->after('payment_mode');
            $table->string('payment_status')
                ->default(Ticket::PAYMENT_STATUS_NOT_REQUIRED)
                ->after('payment_amount');
            $table->text('payment_note')
                ->nullable()
                ->after('payment_status');
            $table->timestamp('payment_requested_at')
                ->nullable()
                ->after('payment_note');
            $table->timestamp('paid_at')
                ->nullable()
                ->after('payment_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table): void {
            $table->dropColumn([
                'payment_mode',
                'payment_amount',
                'payment_status',
                'payment_note',
                'payment_requested_at',
                'paid_at',
            ]);
        });
    }
};

