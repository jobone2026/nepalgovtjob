<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ShareToWhatsApp extends Command
{
    protected $signature = 'share:whatsapp {post_id? : Post ID to share} {--latest : Share latest post} {--type=job : Post type}';
    protected $description = 'Generate WhatsApp share link for posts';

    public function handle()
    {
        $postId = $this->argument('post_id');
        $latest = $this->option('latest');
        $type = $this->option('type');

        if ($latest) {
            $post = Post::where('type', $type)
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->first();
        } elseif ($postId) {
            $post = Post::find($postId);
        } else {
            $this->error('Please provide post_id or use --latest flag');
            return 1;
        }

        if (!$post) {
            $this->error('Post not found');
            return 1;
        }

        // Generate WhatsApp message
        $message = $this->generateWhatsAppMessage($post);
        
        // Generate WhatsApp share link
        $whatsappLink = 'https://wa.me/?text=' . urlencode($message);
        
        // Generate WhatsApp Channel link (if you have a channel)
        $channelLink = \App\Models\SiteSetting::where('key', 'whatsapp_channel')->value('value') ?: 'https://whatsapp.com/channel/0029VbD9cau2P59hFZ1nwh22';
        
        $this->info('📱 WhatsApp Share Links Generated:');
        $this->newLine();
        $this->line('Post: ' . $post->title);
        $this->newLine();
        $this->info('Share Link (Opens WhatsApp):');
        $this->line($whatsappLink);
        $this->newLine();
        $this->info('Message Preview:');
        $this->line('─────────────────────────────────');
        $this->line($message);
        $this->line('─────────────────────────────────');
        $this->newLine();
        $this->info('To share to your WhatsApp Channel:');
        $this->line('1. Open WhatsApp Channel: ' . $channelLink);
        $this->line('2. Copy the message above');
        $this->line('3. Paste and send to your channel');
        
        return 0;
    }

    private function generateWhatsAppMessage($post)
    {
        $url = route('posts.show', [$post->type, $post->slug]);
        
        $emoji = $this->getEmojiForType($post->type);
        
        $message = "{$emoji} *{$post->title}*\n\n";
        
        // Add key details based on post type
        if ($post->type === 'job') {
            if ($post->total_vacancies) {
                $message .= "📊 *Vacancies:* {$post->total_vacancies}\n";
            }
            if ($post->education) {
                $educationLabels = $this->getEducationLabels($post->education);
                $message .= "🎓 *Education:* {$educationLabels}\n";
            }
            if ($post->state_id) {
                $message .= "📍 *State:* {$post->state->name}\n";
            } else {
                $message .= "📍 *Location:* All India\n";
            }
            if ($post->last_date) {
                $message .= "⏰ *Last Date:* " . date('d M Y', strtotime($post->last_date)) . "\n";
            }
        } elseif ($post->type === 'result') {
            $message .= "📋 *Result Announced*\n";
        } elseif ($post->type === 'admit_card') {
            $message .= "🎫 *Admit Card Released*\n";
        }
        
        $message .= "\n🔗 *Apply/Details:*\n{$url}\n\n";
        $message .= "━━━━━━━━━━━━━━━━\n";
        $message .= "📱 *JoBone.in* - Latest Govt Jobs\n";
        $message .= "🔔 Join: https://jobone.in";
        
        return $message;
    }

    private function getEmojiForType($type)
    {
        return match($type) {
            'job' => '💼',
            'result' => '📊',
            'admit_card' => '🎫',
            'answer_key' => '✅',
            'syllabus' => '📚',
            'blog' => '📝',
            default => '📢'
        };
    }

    private function getEducationLabels($education)
    {
        if (empty($education)) {
            return 'Not Specified';
        }

        $labels = [
            '10th' => '10th Pass',
            '12th' => '12th Pass',
            'graduate' => 'Graduate',
            'post_graduate' => 'Post Graduate',
            'diploma' => 'Diploma',
            'iti' => 'ITI',
            'any' => 'Any Qualification'
        ];

        $educationArray = is_array($education) ? $education : json_decode($education, true);
        
        if (!is_array($educationArray)) {
            return 'Not Specified';
        }

        $result = [];
        foreach ($educationArray as $edu) {
            if (isset($labels[$edu])) {
                $result[] = $labels[$edu];
            }
        }

        return !empty($result) ? implode(', ', $result) : 'Not Specified';
    }
}

