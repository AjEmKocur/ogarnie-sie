<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_posts') && ! Schema::hasTable('news_posts')) {
            Schema::rename('blog_posts', 'news_posts');
        }

        if (Schema::hasTable('news_view_events') && Schema::hasColumn('news_view_events', 'blog_post_id')) {
            Schema::table('news_view_events', function (Blueprint $table): void {
                try {
                    $table->dropForeign(['blog_post_id']);
                } catch (\Throwable) {
                }

                try {
                    $table->dropIndex(['blog_post_id', 'viewed_at']);
                } catch (\Throwable) {
                }
            });

            Schema::table('news_view_events', function (Blueprint $table): void {
                $table->renameColumn('blog_post_id', 'news_post_id');
            });

            Schema::table('news_view_events', function (Blueprint $table): void {
                $table->foreign('news_post_id')->references('id')->on('news_posts')->cascadeOnDelete();
                $table->index(['news_post_id', 'viewed_at']);
            });
        }

        $this->migratePermissionKey('cms_blog', 'cms_news');
    }

    public function down(): void
    {
        if (Schema::hasTable('news_posts') && ! Schema::hasTable('blog_posts')) {
            Schema::rename('news_posts', 'blog_posts');
        }

        if (Schema::hasTable('news_view_events') && Schema::hasColumn('news_view_events', 'news_post_id')) {
            Schema::table('news_view_events', function (Blueprint $table): void {
                try {
                    $table->dropForeign(['news_post_id']);
                } catch (\Throwable) {
                }

                try {
                    $table->dropIndex(['news_post_id', 'viewed_at']);
                } catch (\Throwable) {
                }
            });

            Schema::table('news_view_events', function (Blueprint $table): void {
                $table->renameColumn('news_post_id', 'blog_post_id');
            });

            Schema::table('news_view_events', function (Blueprint $table): void {
                $table->foreign('blog_post_id')->references('id')->on('blog_posts')->cascadeOnDelete();
                $table->index(['blog_post_id', 'viewed_at']);
            });
        }

        $this->migratePermissionKey('cms_news', 'cms_blog');
    }

    private function migratePermissionKey(string $from, string $to): void
    {
        $users = DB::table('users')->select('id', 'admin_permissions')->get();

        foreach ($users as $user) {
            $permissions = $this->decodePermissions($user->admin_permissions);
            if ($permissions === null) {
                continue;
            }

            $changed = false;
            foreach ($permissions as &$permission) {
                if ($permission === $from) {
                    $permission = $to;
                    $changed = true;
                }
            }
            unset($permission);

            if (! $changed) {
                continue;
            }

            $permissions = array_values(array_unique($permissions));

            DB::table('users')
                ->where('id', $user->id)
                ->update(['admin_permissions' => json_encode($permissions, JSON_UNESCAPED_UNICODE)]);
        }
    }

    private function decodePermissions(mixed $raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        if (is_array($raw)) {
            return $raw;
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : null;
        }

        return null;
    }
};
