<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        // Check notification status
        $telegramConfigured = env('TELEGRAM_BOT_TOKEN') && env('TELEGRAM_CHANNEL_ID');
        $firebaseConfigured = env('FIREBASE_CREDENTIALS') && file_exists(base_path(env('FIREBASE_CREDENTIALS')));
        $whatsappConfigured = env('WHATSAPP_ACCESS_TOKEN');
        
        return view('admin.notifications.index', compact(
            'telegramConfigured',
            'firebaseConfigured',
            'whatsappConfigured'
        ));
    }
    
    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'url' => 'nullable|url',
            'channels' => 'required|array',
            'channels.*' => 'in:telegram,firebase,whatsapp',
        ]);
        
        $results = [];
        
        // Send to Telegram
        if (in_array('telegram', $validated['channels'])) {
            $results['telegram'] = $this->sendToTelegram(
                $validated['title'],
                $validated['message'],
                $validated['url'] ?? null
            );
        }
        
        // Send to Firebase (Android Push)
        if (in_array('firebase', $validated['channels'])) {
            $results['firebase'] = $this->sendToFirebase(
                $validated['title'],
                $validated['message'],
                $validated['url'] ?? null
            );
        }
        
        // Send to WhatsApp
        if (in_array('whatsapp', $validated['channels'])) {
            $results['whatsapp'] = $this->sendToWhatsApp(
                $validated['title'],
                $validated['message'],
                $validated['url'] ?? null
            );
        }
        
        // Check results
        $success = collect($results)->contains(true);
        
        if ($success) {
            return redirect()->back()->with('success', 'Notification sent successfully! ' . json_encode($results));
        } else {
            return redirect()->back()->with('error', 'Failed to send notification. Check logs for details.');
        }
    }
    
    protected function sendToTelegram($title, $message, $url = null)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $channelId = env('TELEGRAM_CHANNEL_ID');
        
        if (!$botToken || !$channelId) {
            return false;
        }
        
        $text = "📢 *{$title}*\n\n{$message}";
        
        if ($url) {
            $text .= "\n\n🔗 [View Details]({$url})";
        }
        
        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $channelId,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => false,
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }
    
    protected function sendToFirebase($title, $message, $url = null)
    {
        $firebaseCredentials = env('FIREBASE_CREDENTIALS');
        
        if (!$firebaseCredentials || !file_exists(base_path($firebaseCredentials))) {
            return false;
        }
        
        try {
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path($firebaseCredentials));
            $messaging = $factory->createMessaging();
            
            $notification = \Kreait\Firebase\Messaging\Notification::create($title, $message);
            
            $data = ['url' => $url ?? url('/')];
            
            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('topic', 'all_posts')
                ->withNotification($notification)
                ->withData($data);
            
            $messaging->send($message);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase notification failed: ' . $e->getMessage());
            return false;
        }
    }
    
    protected function sendToWhatsApp($title, $message, $url = null)
    {
        $accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
        $channelId = env('WHATSAPP_CHANNEL_ID');
        
        if (!$accessToken || !$phoneNumberId || !$channelId) {
            return false;
        }
        
        $text = "📢 {$title}\n\n{$message}";
        
        if ($url) {
            $text .= "\n\n🔗 {$url}";
        }
        
        try {
            $response = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $channelId,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => true,
                        'body' => $text
                    ]
                ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
