<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDeadlineAlerts extends Command
{
    protected $signature = 'notify:deadline-alerts
                            {--dry-run : Show what would be sent without actually sending}
                            {--days=0,1,2,3 : Comma-separated days to alert for (default: 0,1,2,3)}';

    protected $description = 'Send Telegram alerts for posts whose last_date is today or within 1-3 days';

    public function handle(TelegramService $telegram): int
    {
        $dryRun  = $this->option('dry-run');
        $daysList = array_map('intval', explode(',', $this->option('days')));

        if (!$telegram->isConfigured() && !$dryRun) {
            $this->error('❌ TELEGRAM_BOT_TOKEN or TELEGRAM_CHANNEL_ID not set in .env');
            $this->line('Set them and re-run. Use --dry-run to preview messages without sending.');
            return 1;
        }

        $today = Carbon::today();
        $sent  = 0;
        $skipped = 0;

        foreach ($daysList as $days) {
            $targetDate = $today->copy()->addDays($days);

            $posts = Post::published()
                ->whereDate('last_date', $targetDate)
                ->with('state', 'category')
                ->get();

            if ($posts->isEmpty()) {
                $this->line("📭 No posts with last_date = {$targetDate->format('d M Y')} (+{$days} days)");
                continue;
            }

            $this->info("📬 Found {$posts->count()} post(s) with last_date = {$targetDate->format('d M Y')} (+{$days} days)");

            foreach ($posts as $post) {
                $message = TelegramService::buildDeadlineMessage($post, $days);

                if ($dryRun) {
                    $this->line("\n── DRY RUN ──────────────────────────────────");
                    $this->line("Post: {$post->title}");
                    $this->line("Message preview:\n{$message}");
                    $this->line("─────────────────────────────────────────────\n");
                    $sent++;
                    continue;
                }

                // Rate-limit: 1 message per second (Telegram limit = 30/sec per bot)
                if ($sent > 0) sleep(1);

                $ok = $telegram->sendMessage($message);

                if ($ok) {
                    $this->info("  ✅ Sent: [{$post->type}] {$post->title}");
                    Log::info("DeadlineAlert sent for post #{$post->id}: {$post->title}");
                    $sent++;
                } else {
                    $this->warn("  ⚠️  Failed: [{$post->type}] {$post->title}");
                    $skipped++;
                }
            }
        }

        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Sent: {$sent}  |  ⚠️  Failed: {$skipped}");

        return 0;
    }
}
