<?php

namespace App\Support;

use App\Models\ContactMessage;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TicketNotificationCenter
{
    /**
     * @return array{total:int,items:array<int,array{title:string,url:string,time:string}>}
     */
    public static function forUser(?User $user): array
    {
        if (! $user) {
            return ['total' => 0, 'items' => []];
        }

        return $user->isAdmin()
            ? self::forAdmin($user)
            : self::forClient($user);
    }

    /**
     * @return array{total:int,items:array<int,array{title:string,url:string,time:string}>}
     */
    private static function forClient(User $user): array
    {
        $rows = Ticket::query()
            ->where('user_id', $user->id)
            ->select(['id', 'title', 'client_last_seen_at'])
            ->selectSub(function ($q): void {
                $q->from('ticket_messages')
                    ->join('users', 'users.id', '=', 'ticket_messages.user_id')
                    ->whereColumn('ticket_messages.ticket_id', 'tickets.id')
                    ->whereIn('users.role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
                    ->selectRaw('max(ticket_messages.created_at)');
            }, 'last_admin_message_at')
            ->selectSub(function ($q): void {
                $q->from('ticket_status_histories')
                    ->join('users', 'users.id', '=', 'ticket_status_histories.changed_by_user_id')
                    ->whereColumn('ticket_status_histories.ticket_id', 'tickets.id')
                    ->whereIn('users.role', [User::ROLE_ADMIN, User::ROLE_OPERATOR])
                    ->selectRaw('max(ticket_status_histories.created_at)');
            }, 'last_admin_status_at')
            ->get();

        $unread = $rows->filter(function ($row): bool {
            $seen = $row->client_last_seen_at ? Carbon::parse($row->client_last_seen_at) : null;
            $lastAdminMessage = $row->last_admin_message_at ? Carbon::parse($row->last_admin_message_at) : null;
            $lastAdminStatus = $row->last_admin_status_at ? Carbon::parse($row->last_admin_status_at) : null;

            if (! $lastAdminMessage && ! $lastAdminStatus) {
                return false;
            }

            $latest = collect([$lastAdminMessage, $lastAdminStatus])->filter()->max();
            if (! $latest) {
                return false;
            }

            return ! $seen || $latest->gt($seen);
        })->sortByDesc(function ($row) {
            $times = collect([$row->last_admin_message_at, $row->last_admin_status_at])->filter();
            return $times->isEmpty() ? null : Carbon::parse($times->max());
        });

        return self::buildPayload($unread, false);
    }

    /**
     * @return array{total:int,items:array<int,array{title:string,url:string,time:string}>}
     */
    private static function forAdmin(User $user): array
    {
        $items = collect();

        if ($user->hasAdminPermission('tickets')) {
            $rows = Ticket::query()
                ->select(['id', 'title', 'admin_last_seen_at'])
                ->selectSub(function ($q): void {
                    $q->from('ticket_messages')
                        ->join('users', 'users.id', '=', 'ticket_messages.user_id')
                        ->whereColumn('ticket_messages.ticket_id', 'tickets.id')
                        ->where('users.role', User::ROLE_CLIENT)
                        ->selectRaw('max(ticket_messages.created_at)');
                }, 'last_client_message_at')
                ->whereExists(function ($q): void {
                    $q->select(DB::raw(1))
                        ->from('ticket_messages')
                        ->join('users', 'users.id', '=', 'ticket_messages.user_id')
                        ->whereColumn('ticket_messages.ticket_id', 'tickets.id')
                        ->where('users.role', User::ROLE_CLIENT);
                })
                ->get();

            $ticketItems = $rows->filter(function ($row): bool {
                if (! $row->last_client_message_at) {
                    return false;
                }

                $seen = $row->admin_last_seen_at ? Carbon::parse($row->admin_last_seen_at) : null;
                $lastClientMessage = Carbon::parse($row->last_client_message_at);

                return ! $seen || $lastClientMessage->gt($seen);
            })->map(function ($row): array {
                return [
                    'title' => (string) $row->title,
                    'url' => route('admin.tickets.show', $row->id),
                    'time' => Carbon::parse($row->last_client_message_at)->format('Y-m-d H:i'),
                    '_sort' => Carbon::parse($row->last_client_message_at),
                ];
            });

            $items = $items->merge($ticketItems);
        }

        if ($user->hasAdminPermission('contact_messages')) {
            $contactSeenAt = $user->contact_messages_last_seen_at ? Carbon::parse($user->contact_messages_last_seen_at) : null;
            $contactItems = ContactMessage::query()
                ->where('status', ContactMessage::STATUS_NEW)
                ->when($contactSeenAt, function ($query) use ($contactSeenAt): void {
                    $query->where('created_at', '>', $contactSeenAt);
                })
                ->latest()
                ->get()
                ->map(function (ContactMessage $message): array {
                    return [
                        'title' => 'Kontakt: '.$message->subject,
                        'url' => route('admin.contact.index').'#message-'.$message->id,
                        'time' => $message->created_at?->format('Y-m-d H:i') ?? '',
                        '_sort' => $message->created_at,
                    ];
                });

            $items = $items->merge($contactItems);
        }

        $sorted = $items->sortByDesc(function (array $item) {
            return $item['_sort'] ?? null;
        })->values();

        return [
            'total' => $sorted->count(),
            'items' => $sorted->take(8)->map(function (array $item): array {
                return [
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'time' => $item['time'],
                ];
            })->all(),
        ];
    }

    /**
     * @param Collection<int, mixed> $rows
     * @return array{total:int,items:array<int,array{title:string,url:string,time:string}>}
     */
    private static function buildPayload(Collection $rows, bool $admin): array
    {
        $items = $rows->take(8)->map(function ($row) use ($admin): array {
            $latest = collect([
                $row->last_client_message_at ?? null,
                $row->last_admin_message_at ?? null,
                $row->last_admin_status_at ?? null,
            ])->filter()->max();

            return [
                'title' => (string) $row->title,
                'url' => $admin
                    ? route('admin.tickets.show', $row->id)
                    : route('client.tickets.show', $row->id),
                'time' => $latest ? Carbon::parse($latest)->format('Y-m-d H:i') : '',
            ];
        })->values()->all();

        return [
            'total' => $rows->count(),
            'items' => $items,
        ];
    }
}
