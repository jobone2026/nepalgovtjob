<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    protected $signature = 'notification:test';
    protected $description = 'Test notification system';

    public function handle()
    {
        $this->info('🔔 Testing Notification System...');
        $this->newLine();
        
        // Get the latest post
        $post = Post::latest()->first();
        
        if (!$post) {
            $this->error('❌ No posts found in database!');
            $this->info('💡 Create a post first in admin panel');
            return 1;
        }
        
        $this->info('📝 Testing with post: ' . $post->title);
        $this->newLine();
        
        // Test Telegram
        $this->info('📱 Testing Telegram...');
        if (config('notifications.telegram.bot_token') && config('notifications.telegram.channel_id')) {
            try {
                $notificationService = app(NotificationService::class);
                $result = $notificationService->sendNewPostNotifications($post);
                
                if ($result) {
                    $this->info('✅ Telegram notification sent successfully!');
                    $this->info('   Check your Telegram channel: ' . config('notifications.telegram.channel_id'));
                } else {
                    $this->warn('⚠️  Telegram notification failed. Check logs.');
                }
            } catch (\Exception $e) {
                $this->error('❌ Telegram error: ' . $e->getMessage());
            }
        } else {
            $this->warn('⚠️  Telegram not configured');
            $this->info('   Add TELEGRAM_BOT_TOKEN and TELEGRAM_CHANNEL_ID to .env');
        }
        
        $this->newLine();
        
        // Test Firebase (Android Push)
        $this->info('📱 Testing Firebase (Android Push)...');
        if (config('notifications.firebase.credentials')) {
            $credPath = base_path(config('notifications.firebase.credentials'));
            if (file_exists($credPath)) {
                $this->info('✅ Firebase credentials found');
                
                // Actually test sending a notification
                try {
                    $notificationService = app(NotificationService::class);
                    
                    // Use reflection to call the protected method
                    $reflection = new \ReflectionClass($notificationService);
                    $method = $reflection->getMethod('sendAndroidPushNotification');
                    $method->setAccessible(true);
                    
                    $result = $method->invoke($notificationService, $post);
                    
                    if ($result) {
                        $this->info('✅ Android push notification sent successfully!');
                        $this->info('   Check your Android app for the notification');
                        $this->info('   Topic: all_posts');
                    } else {
                        $this->warn('⚠️  Android push notification failed. Check logs.');
                    }
                } catch (\Exception $e) {
                    $this->error('❌ Firebase error: ' . $e->getMessage());
                    $this->info('   Check your Firebase project configuration');
                }
            } else {
                $this->warn('⚠️  Firebase credentials file not found at: ' . $credPath);
            }
        } else {
            $this->warn('⚠️  Firebase not configured');
            $this->info('   Add FIREBASE_CREDENTIALS to .env');
        }
        
        $this->newLine();
        
        // Test WhatsApp
        $this->info('💬 Testing WhatsApp...');
        if (config('notifications.whatsapp.access_token')) {
            $this->info('✅ WhatsApp configured');
        } else {
            $this->warn('⚠️  WhatsApp not configured (optional)');
            $this->info('   Use WhatsApp Channel manually or add API credentials');
        }
        
        $this->newLine();
        $this->info('📊 Test Summary:');
        $this->table(
            ['Service', 'Status', 'Action'],
            [
                ['Telegram', config('notifications.telegram.bot_token') ? '✅ Ready' : '❌ Not configured', 'Check channel'],
                ['Firebase', config('notifications.firebase.credentials') ? '✅ Ready' : '❌ Not configured', 'Check Android app'],
                ['WhatsApp', config('notifications.whatsapp.access_token') ? '✅ Ready' : '⚠️  Optional', 'Manual or API'],
            ]
        );
        
        $this->newLine();
        $this->info('💡 Troubleshooting Tips:');
        $this->info('1. Make sure your Android app is subscribed to "all_posts" topic');
        $this->info('2. Check Firebase Console > Cloud Messaging for delivery stats');
        $this->info('3. Ensure your app handles FLUTTER_NOTIFICATION_CLICK action');
        $this->info('4. Check Laravel logs: storage/logs/laravel.log');
        
        return 0;
    }
}
