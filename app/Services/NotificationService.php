<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notifications when a new post is published
     */
    public function sendNewPostNotifications(Post $post)
    {
        try {
            // Load state relationship if not already loaded
            if (!$post->relationLoaded('state')) {
                $post->load('state');
            }
            
            // Send to Telegram
            $this->sendToTelegram($post);
            
            // Send to WhatsApp (if configured)
            $this->sendToWhatsApp($post);
            
            // Send Android Push Notification
            $this->sendAndroidPushNotification($post);
            
            Log::info('Notifications sent successfully for post: ' . $post->id);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notifications: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send notification to Telegram Channel
     */
    /**
     * Build type-specific notification message
     */
    protected function buildMessage(Post $post, string $postUrl): string
    {
        $type      = $post->type;
        $title     = htmlspecialchars($post->title, ENT_QUOTES);
        $org       = $post->organization ? htmlspecialchars($post->organization, ENT_QUOTES) : null;
        $state     = $post->state ? htmlspecialchars($post->state->name, ENT_QUOTES) : 'All India';
        $typeInfo  = $this->getTypeInfo($type);
        $em        = $typeInfo['emoji'];

        // Use HTML format (not Markdown V1) — Markdown V1 corrupts URLs with hyphens/numbers
        $msg = "{$em} <b>{$typeInfo['title']}</b> {$em}\n";
        $msg .= "━━━━━━━━━━━━━━━━\n\n";
        $msg .= "\u{1F525} <b>{$title}</b>\n";
        if ($org) $msg .= "\u{1F3E2} <b>Org:</b> {$org}\n";
        $msg .= "\u{1F4CD} <b>State:</b> {$state}\n";

        if ($type === 'job') {
            if ($post->total_posts)      $msg .= "\u{1F465} <b>Vacancies:</b> {$post->total_posts}\n";
            if ($post->salary)           $msg .= "\u{1F4B0} <b>Salary:</b> " . htmlspecialchars($post->salary, ENT_QUOTES) . "\n";
            if ($post->start_date)       $msg .= "\u{1F7E3} <b>Apply Start:</b> " . $post->start_date->format('d-m-Y') . "\n";
            if ($post->last_date)        $msg .= "\u{1F534} <b>Last Date:</b> " . $post->last_date->format('d-m-Y') . "\n";
            if (!empty($post->education) && is_array($post->education)) {
                $edu = $this->getEducationLabels($post->education);
                if ($edu) $msg .= "\u{1F393} <b>Qualification:</b> " . htmlspecialchars(implode(', ', $edu), ENT_QUOTES) . "\n";
            }
            $msg .= "\n\u{1F517} <b>Apply Now:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } elseif ($type === 'admit_card') {
            if ($post->notification_date) $msg .= "\u{1F4C5} <b>Released:</b> " . $post->notification_date->format('d-m-Y') . "\n";
            if ($post->last_date)         $msg .= "\u{23F0} <b>Available Till:</b> " . $post->last_date->format('d-m-Y') . "\n";
            $msg .= "\n\u{1F3AB} <b>Download Admit Card:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } elseif ($type === 'result') {
            if ($post->notification_date) $msg .= "\u{1F4C5} <b>Result Date:</b> " . $post->notification_date->format('d-m-Y') . "\n";
            $msg .= "\n\u{1F4CA} <b>Check Result:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } elseif ($type === 'answer_key') {
            if ($post->notification_date) $msg .= "\u{1F4C5} <b>Released:</b> " . $post->notification_date->format('d-m-Y') . "\n";
            if ($post->last_date)         $msg .= "\u{23F0} <b>Objection Last Date:</b> " . $post->last_date->format('d-m-Y') . "\n";
            $msg .= "\n\u{1F511} <b>Download Answer Key:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } elseif ($type === 'syllabus') {
            if (!empty($post->education) && is_array($post->education)) {
                $edu = $this->getEducationLabels($post->education);
                if ($edu) $msg .= "\u{1F393} <b>Qualification:</b> " . htmlspecialchars(implode(', ', $edu), ENT_QUOTES) . "\n";
            }
            $msg .= "\n\u{1F4DA} <b>Download Syllabus:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } elseif ($type === 'scholarship') {
            if ($post->salary)    $msg .= "\u{1F4B0} <b>Amount:</b> " . htmlspecialchars($post->salary, ENT_QUOTES) . "\n";
            if ($post->last_date) $msg .= "\u{1F534} <b>Last Date:</b> " . $post->last_date->format('d-m-Y') . "\n";
            $msg .= "\n\u{1F393} <b>Apply for Scholarship:</b> <a href=\"{$postUrl}\">Click Here</a>\n";

        } else { // blog, other
            $msg .= "\n\u{1F4D6} <b>Read More:</b> <a href=\"{$postUrl}\">Click Here</a>\n";
        }

        $typeTag = str_replace('_', '', $type);
        $msg .= "\n#jobone #jobone2026 #{$typeTag} #sarkarinaukri";

        return $msg;
    }

    /**
     * Send notification to Telegram Channel
     */
    protected function sendToTelegram(Post $post)
    {
        $botToken  = config('notifications.telegram.bot_token');
        $channelId = config('notifications.telegram.channel_id');

        if (!$botToken || !$channelId) {
            Log::warning('Telegram credentials not configured');
            return false;
        }

        try {
            // Use the actual post type for correct URL generation
            $postUrl = route('posts.show', [$post->type, $post->slug]);
            $message = $this->buildMessage($post, $postUrl);
            $imgUrl  = $post->featured_image ?? null;

            if ($imgUrl) {
                // Send as photo with caption — use HTML parse_mode (Markdown V1 corrupts URLs)
                $response = Http::post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                    'chat_id'    => $channelId,
                    'photo'      => $imgUrl,
                    'caption'    => $message,
                    'parse_mode' => 'HTML',
                ]);
                
                // If photo fails, fallback to text message
                if (!$response->successful()) {
                    Log::warning('Telegram photo failed, sending as text: ' . $response->body());
                    $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id'                  => $channelId,
                        'text'                     => $message,
                        'parse_mode'               => 'HTML',
                        'disable_web_page_preview' => false,
                    ]);
                }
            } else {
                $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id'                  => $channelId,
                    'text'                     => $message,
                    'parse_mode'               => 'HTML',
                    'disable_web_page_preview' => false,
                ]);
            }

            if ($response->successful()) {
                Log::info('Telegram notification sent for post: ' . $post->id);
                return true;
            } else {
                Log::error('Telegram API error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to WhatsApp Channel using WhatsApp Business API
     */
    protected function sendToWhatsApp(Post $post)
    {
        $accessToken   = config('notifications.whatsapp.access_token');
        $phoneNumberId = config('notifications.whatsapp.phone_number_id');
        $channelId     = config('notifications.whatsapp.channel_id');

        if (!$accessToken || !$phoneNumberId || !$channelId) {
            Log::warning('WhatsApp credentials not configured');
            return false;
        }

        try {
            // Use the actual post type for correct URL generation
            $postUrl = route('posts.show', [$post->type, $post->slug]);
            // WhatsApp doesn't support Markdown escaping the same way — strip escape chars
            $message = str_replace('\\', '', $this->buildMessage($post, $postUrl));

            $response = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $channelId,
                    'type'              => 'text',
                    'text'              => [
                        'preview_url' => true,
                        'body'        => $message,
                    ]
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp notification sent for post: ' . $post->id);
                return true;
            } else {
                Log::error('WhatsApp API error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get type-specific information for notifications
     */
    protected function getTypeInfo(string $type): array
    {
        return match($type) {
            'job'        => ['emoji' => "\u{1F4BC}", 'title' => 'New Job Vacancy'],
            'admit_card' => ['emoji' => "\u{1F3AB}", 'title' => 'New Admit Card'],
            'result'     => ['emoji' => "\u{1F4CA}", 'title' => 'New Result'],
            'answer_key' => ['emoji' => "\u{1F511}", 'title' => 'New Answer Key'],
            'syllabus'   => ['emoji' => "\u{1F4DA}", 'title' => 'New Syllabus'],
            'scholarship'=> ['emoji' => "\u{1F393}", 'title' => 'New Scholarship'],
            'blog'       => ['emoji' => "\u{1F4DD}", 'title' => 'New Article'],
            default      => ['emoji' => "\u{1F4E2}", 'title' => 'New Update'],
        };
    }

    /**
     * Send Android Push Notification using Firebase Admin SDK
     */
    protected function sendAndroidPushNotification(Post $post)
    {
        $firebaseCredentials = config('notifications.firebase.credentials');
        
        if (!$firebaseCredentials || !file_exists(base_path($firebaseCredentials))) {
            Log::warning('Firebase credentials not configured');
            return false;
        }
        
        try {
            // Initialize Firebase Admin SDK
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path($firebaseCredentials));
            $messaging = $factory->createMessaging();
            
            // Use the actual post type for correct URL generation
            $postUrl = route('posts.show', [$post->type, $post->slug]);
            $emoji = $this->getEmojiForType($post->type);
            
            // Create notification message with clickable link
            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('topic', 'all_posts')
                ->withNotification(\Kreait\Firebase\Messaging\Notification::create(
                    $emoji . ' New ' . ucfirst(str_replace('_', ' ', $post->type)),
                    $post->title
                ))
                ->withData([
                    'post_id' => (string) $post->id,
                    'post_type' => $post->type,
                    'post_slug' => $post->slug,
                    'url' => $postUrl,
                    'title' => $post->title,
                    'click_action' => 'OPEN_POST',
                ])
                ->withAndroidConfig([
                    'priority' => 'high',
                    'notification' => [
                        'icon' => 'ic_notification',
                        'color' => '#2563eb',
                        'sound' => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'channel_id' => 'high_importance_channel',
                        'tag' => 'post_' . $post->id,
                    ],
                    'data' => [
                        'url' => $postUrl,
                        'action' => 'open_url',
                    ]
                ]);
            
            $messaging->send($message);
            
            Log::info('Android push notification sent for post: ' . $post->id . ' with URL: ' . $postUrl);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Android push notification failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send Web Push Notification using Firebase Admin SDK
     */
    protected function sendWebPushNotification(Post $post)
    {
        $firebaseCredentials = config('notifications.firebase.credentials');
        
        if (!$firebaseCredentials || !file_exists(base_path($firebaseCredentials))) {
            Log::warning('Firebase credentials not configured');
            return false;
        }
        
        try {
            // Initialize Firebase Admin SDK
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path($firebaseCredentials));
            $messaging = $factory->createMessaging();
            
            // Use the actual post type for correct URL generation
            $postUrl = route('posts.show', [$post->type, $post->slug]);
            
            // Create notification message
            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('topic', 'all_posts')
                ->withNotification([
                    'title' => "\u{1F514} New " . ucfirst(str_replace('_', ' ', $post->type)), // 🔔
                    'body' => $post->title,
                    'image' => asset('images/jobone-logo.png'),
                ])
                ->withData([
                    'post_id' => (string) $post->id,
                    'post_type' => $post->type,
                    'url' => $postUrl,
                    'click_action' => $postUrl,
                ]);
            
            $messaging->send($message);
            
            Log::info('Web push notification sent for post: ' . $post->id);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Web push notification failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get emoji based on post type
     */
    protected function getEmojiForType($type)
    {
        return match($type) {
            'job' => "\u{1F4BC}",
            'admit_card' => "\u{1F3AB}",
            'result' => "\u{1F4CA}",
            'answer_key' => "\u{1F511}",
            'syllabus' => "\u{1F4DA}",
            'blog' => "\u{1F4DD}",
            default => "\u{1F4E2}",
        };
    }
    
    /**
     * Escape markdown special characters for Telegram
     */
    protected function escapeMarkdown($text)
    {
        $specialChars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($specialChars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }
        return $text;
    }
    
    /**
     * Get readable education labels from education codes
     */
    protected function getEducationLabels($educationArray)
    {
        $labels = [
            '10th_pass' => '10th Pass',
            '12th_pass' => '12th Pass',
            'graduate' => 'Graduate',
            'post_graduate' => 'Post Graduate',
            'diploma' => 'Diploma',
            'iti' => 'ITI',
            'btech' => 'B.Tech/B.E',
            'mtech' => 'M.Tech/M.E',
            'bsc' => 'B.Sc',
            'msc' => 'M.Sc',
            'bcom' => 'B.Com',
            'mcom' => 'M.Com',
            'ba' => 'B.A',
            'ma' => 'M.A',
            'bba' => 'BBA',
            'mba' => 'MBA',
            'ca' => 'CA',
            'cs' => 'CS',
            'cma' => 'CMA',
            'llb' => 'LLB',
            'llm' => 'LLM',
            'mbbs' => 'MBBS',
            'bds' => 'BDS',
            'bpharm' => 'B.Pharm',
            'mpharm' => 'M.Pharm',
            'nursing' => 'Nursing',
            'bed' => 'B.Ed',
            'med' => 'M.Ed',
            'phd' => 'PhD',
            'any_qualification' => 'Any Qualification',
        ];
        
        $result = [];
        foreach ($educationArray as $code) {
            if (isset($labels[$code])) {
                $result[] = $labels[$code];
            }
        }
        
        return $result;
    }
}