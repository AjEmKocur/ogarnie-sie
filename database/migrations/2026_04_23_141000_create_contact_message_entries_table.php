<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_message_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contact_message_id')->constrained('contact_messages')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_type', 20);
            $table->text('message');
            $table->timestamps();
        });

        DB::table('contact_messages')
            ->select([
                'id',
                'user_id',
                'message',
                'created_at',
                'updated_at',
                'reply_message',
                'replied_by_user_id',
                'replied_at',
            ])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                $inserts = [];

                foreach ($rows as $row) {
                    $createdAt = $row->created_at ?? now();

                    if (! empty($row->message)) {
                        $inserts[] = [
                            'contact_message_id' => $row->id,
                            'user_id' => $row->user_id,
                            'sender_type' => 'client',
                            'message' => $row->message,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ];
                    }

                    if (! empty($row->reply_message)) {
                        $replyAt = $row->replied_at ?? $row->updated_at ?? now();
                        $inserts[] = [
                            'contact_message_id' => $row->id,
                            'user_id' => $row->replied_by_user_id,
                            'sender_type' => 'admin',
                            'message' => $row->reply_message,
                            'created_at' => $replyAt,
                            'updated_at' => $replyAt,
                        ];
                    }
                }

                if ($inserts !== []) {
                    DB::table('contact_message_entries')->insert($inserts);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_message_entries');
    }
};
