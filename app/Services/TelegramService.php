<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $chatId;
    private string $apiBase;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
        $this->chatId   = config('services.telegram.chat_id', '');
        $this->apiBase  = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function isConfigured(): bool
    {
        return !empty($this->botToken) && !empty($this->chatId);
    }

    /**
     * Send a message to the Telegram channel.
     */
    public function sendMessage(string $text, array $options = []): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('TelegramService: BOT_TOKEN or CHANNEL_ID not configured.');
            return false;
        }

        try {
            $payload = array_merge([
                'chat_id'                  => $this->chatId,
                'text'                     => $text,
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => false,
            ], $options);

            $response = Http::timeout(10)
                ->post("{$this->apiBase}/sendMessage", $payload);

            if (!$response->successful()) {
                Log::error('Telegram sendMessage failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('TelegramService exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build a deadline alert message for a post.
     */
    public static function buildDeadlineMessage(\App\Models\Post $post, int $daysLeft): string
    {
        // Use the actual post type for correct URL generation
        $url      = route('posts.show', [$post->type, $post->slug]);
        $cleanTitle = \App\Helpers\PostHelper::cleanTitle($post->title);

        // Urgency header
        if ($daysLeft === 0) {
            $header = "\u{1F525}\u{1F525} <b>LAST DATE TODAY!</b> \u{1F525}\u{1F525}"; // 🔥🔥
            $urgencyNote = "\u{26A0}\u{FE0F} <b>Apply RIGHT NOW \u2014 form closes tonight!</b>"; // ⚠️
        } elseif ($daysLeft === 1) {
            $header = "\u{1F525} <b>LAST DATE TOMORROW!</b>"; // 🔥
            $urgencyNote = "\u{26A0}\u{FE0F} Only <b>1 day left</b> \u2014 don't miss it!"; // ⚠️
        } else {
            $header = "\u{1F525} <b>DEADLINE APPROACHING</b>"; // 🔥
            $urgencyNote = "\u{23F3} Only <b>{$daysLeft} days left</b> to apply!"; // ⏳
        }

        $typeEmoji = match($post->type) {
            'job'         => "\u{1F4BC}", // 💼
            'admit_card'  => "\u{1F3AB}", // 🎟️
            'result'      => "\u{1F3C6}", // 🏆
            'answer_key'  => "\u{1F511}", // 🔑
            'syllabus'    => "\u{1F4DA}", // 📚
            'scholarship' => "\u{1F393}", // 🎓
            default       => "\u{1F4E2}", // 📢
        };

        $typeLabel = match($post->type) {
            'job'         => 'Job',
            'admit_card'  => 'Admit Card',
            'result'      => 'Result',
            'answer_key'  => 'Answer Key',
            'syllabus'    => 'Syllabus',
            'scholarship' => 'Scholarship',
            default       => ucfirst($post->type),
        };

        $msg  = "{$header}\n\n";
        $msg .= "{$typeEmoji} <b>{$cleanTitle}</b>\n\n";

        if ($post->organization) {
            $msg .= "\u{1F3DB}\u{FE0F} <b>Organisation:</b> {$post->organization}\n"; // 🏛️
        }
        if ($post->type === 'job' || $post->type === 'scholarship') {
            if ($post->total_posts) {
                $msg .= "\u{1F4CA} <b>Vacancies:</b> " . number_format($post->total_posts) . " Posts\n"; // 📊
            }
            if ($post->salary) {
                $msg .= "\u{1F4B0} <b>Salary:</b> {$post->salary}\n"; // 💰
            }
        }
        if ($post->state) {
            $msg .= "\u{1F4CD} <b>State:</b> {$post->state->name}\n"; // 📍
        } else {
            $msg .= "\u{1F4CD} <b>Location:</b> All India\n"; // 📍
        }

        $lastDateStr = $post->last_date->format('d M Y');
        $msg .= "\u{1F4C5} <b>Last Date:</b> <u>{$lastDateStr}</u>\n\n"; // 📅
        $msg .= "{$urgencyNote}\n\n";
        $msg .= "\u{1F517} <b>View {$typeLabel}:</b>\n<a href=\"{$url}\">{$url}</a>\n\n"; // 🔗
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "\u{1F4CC} <a href=\"https://jobone.in\">JobOne.in</a> \u2014 Sarkari Naukri & Govt Jobs\n"; // 📌
        $msg .= "\u{1F514} <a href=\"https://t.me/jobone2026\">Join Telegram @jobone2026</a>"; // 🔔

        return $msg;
    }

    /**
     * Build a "Date Extended" alert message.
     */
    public static function buildDateExtendedMessage(\App\Models\Post $post): string
    {
        // Use the actual post type for correct URL generation
        $url        = route('posts.show', [$post->type, $post->slug]);
        $cleanTitle = \App\Helpers\PostHelper::cleanTitle($post->title);
        $newDate    = $post->last_date->format('d M Y');

        $typeEmoji = match($post->type) {
            'job'         => "\u{1F4BC}", // 💼
            'admit_card'  => "\u{1F3AB}", // 🎟️
            'result'      => "\u{1F3C6}", // 🏆
            'answer_key'  => "\u{1F511}", // 🔑
            'syllabus'    => "\u{1F4DA}", // 📚
            'scholarship' => "\u{1F393}", // 🎓
            default       => "\u{1F4E2}", // 📢
        };

        $msg  = "\u{1F525} <b>DATE EXTENDED!</b> \u{2705}\n\n"; // 🔥 ✅
        $msg .= "{$typeEmoji} <b>{$cleanTitle}</b>\n\n";

        if ($post->organization) {
            $msg .= "\u{1F3DB}\u{FE0F} <b>Organisation:</b> {$post->organization}\n"; // 🏛️
        }
        if (in_array($post->type, ['job', 'scholarship'])) {
            if ($post->total_posts) {
                $msg .= "\u{1F4CA} <b>Vacancies:</b> " . number_format($post->total_posts) . " Posts\n"; // 📊
            }
            if ($post->salary) {
                $msg .= "\u{1F4B0} <b>Salary:</b> {$post->salary}\n"; // 💰
            }
        }
        if ($post->state) {
            $msg .= "\u{1F4CD} <b>State:</b> {$post->state->name}\n"; // 📍
        } else {
            $msg .= "\u{1F4CD} <b>Location:</b> All India\n"; // 📍
        }

        $msg .= "\u{1F4C5} <b>New Last Date:</b> <u>{$newDate}</u>\n\n"; // 📅
        $msg .= "\u{2705} <b>Good news! You still have time to apply.</b>\n"; // ✅
        $msg .= "\u{26A1} Apply before the new deadline!\n\n"; // ⚡
        $msg .= "\u{1F517} <b>Apply / View Details:</b>\n<a href=\"{$url}\">{$url}</a>\n\n"; // 🔗
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "\u{1F4CC} <a href=\"https://jobone.in\">JobOne.in</a> \u2014 Sarkari Naukri & Govt Jobs\n"; // 📌
        $msg .= "\u{1F514} <a href=\"https://t.me/jobone2026\">Join Telegram @jobone2026</a>"; // 🔔

        return $msg;
    }
}
