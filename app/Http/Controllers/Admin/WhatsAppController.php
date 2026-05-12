<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.whatsapp.index', compact('posts'));
    }

    public function generateMessage($id)
    {
        $post = Post::findOrFail($id);
        
        $message = $this->generateWhatsAppMessage($post);
        $whatsappLink = 'https://wa.me/?text=' . urlencode($message);
        
        return response()->json([
            'message' => $message,
            'link' => $whatsappLink,
            'post' => [
                'title' => $post->title,
                'url' => route('posts.show', [$post->type, $post->slug])
            ]
        ]);
    }

    private function generateWhatsAppMessage($post)
    {
        // Use the actual post type for correct URL generation
        $url = route('posts.show', [$post->type, $post->slug]);
        
        $typeInfo = match($post->type) {
            'job' => ['emoji' => "\u{1F4BC}", 'title' => 'New Job Vacancy', 'date_label' => 'Application Start'],
            'admit_card' => ['emoji' => "\u{1F3AB}", 'title' => 'New Admit Card', 'date_label' => 'Release Date'],
            'result' => ['emoji' => "\u{1F4CA}", 'title' => 'New Result', 'date_label' => 'Result Date'],
            'answer_key' => ['emoji' => "\u{1F511}", 'title' => 'New Answer Key', 'date_label' => 'Release Date'],
            'syllabus' => ['emoji' => "\u{1F4DA}", 'title' => 'New Syllabus', 'date_label' => 'Release Date'],
            'blog' => ['emoji' => "\u{1F4DD}", 'title' => 'New Article', 'date_label' => 'Published Date'],
            default => ['emoji' => "\u{1F4E2}", 'title' => 'New Update', 'date_label' => 'Date']
        };
        
        $message = "{$typeInfo['emoji']} *{$typeInfo['title']}* {$typeInfo['emoji']}\n";
        $message .= "━━━━━━━━━━━━━━━━\n\n";
        $message .= "\u{1F525} *{$post->title}*\n\n";
        
        // Add state
        $stateName = $post->state ? $post->state->name : 'All India';
        $message .= "\u{1F4CD} *State:* {$stateName}\n";
        
        // Add vacancies
        if ($post->total_posts) {
            $message .= "\u{1F465} *Total Posts:* {$post->total_posts}\n";
        }
        
        // Add Application Date
        if ($post->notification_date) {
            $message .= "\u{1F7E3} *{$typeInfo['date_label']}:* " . date('d-m-Y', strtotime($post->notification_date)) . "\n";
        }

        // Add Last Date
        if ($post->last_date) {
            $message .= "\u{1F7E2} *Last Date:* " . date('d-m-Y', strtotime($post->last_date)) . "\n";
        } else {
            $message .= "\u{1F7E2} *Last Date:* -\n";
        }
        
        // Add education
        if ($post->education) {
            $educationLabels = $this->getEducationLabels($post->education);
            $message .= "\u{1F393} *Education:* {$educationLabels}\n";
        }
        
        $message .= "\n\u{27A1}\u{FE0F} *Apply Here:* {$url}\n\n";
        $message .= "#jobone2026 #jobone #{$post->type}";
        
        return $message;
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
