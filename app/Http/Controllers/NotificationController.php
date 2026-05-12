<?php

namespace App\Http\Controllers;

use App\Models\NotificationSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Subscribe to web notifications
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string|max:500',
            'keys.p256dh' => 'required|string|max:255',
            'keys.auth' => 'required|string|max:255',
        ]);

        try {
            $subscription = NotificationSubscription::updateOrCreate(
                ['endpoint' => $validated['endpoint']],
                [
                    'p256dh' => $validated['keys']['p256dh'],
                    'auth' => $validated['keys']['auth'],
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                    'is_active' => true,
                ]
            );

            Log::info('Web notification subscription created', [
                'id' => $subscription->id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to notifications!',
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to subscribe to notifications: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to notifications'
            ], 500);
        }
    }

    /**
     * Unsubscribe from web notifications
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string|max:500',
        ]);

        try {
            $subscription = NotificationSubscription::where('endpoint', $validated['endpoint'])->first();

            if ($subscription) {
                $subscription->update(['is_active' => false]);
                
                Log::info('Web notification unsubscribed', [
                    'id' => $subscription->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully unsubscribed from notifications'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Failed to unsubscribe: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe'
            ], 500);
        }
    }

    /**
     * Check subscription status
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string|max:500',
        ]);

        $subscription = NotificationSubscription::where('endpoint', $validated['endpoint'])
            ->where('is_active', true)
            ->first();

        return response()->json([
            'subscribed' => $subscription !== null,
            'last_notified' => $subscription?->last_notified_at?->diffForHumans()
        ]);
    }

    /**
     * Submit feedback
     */
    public function feedback(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bug,feature,general',
            'message' => 'required|string|max:1000',
            'email' => 'nullable|email|max:255',
            'page_url' => 'nullable|string|max:500',
        ]);

        try {
            // Log feedback
            Log::channel('single')->info('User Feedback Received', [
                'type' => $validated['type'],
                'message' => $validated['message'],
                'email' => $validated['email'] ?? 'anonymous',
                'page_url' => $validated['page_url'] ?? 'unknown',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // You can also save to database or send email here
            // For now, we're just logging it

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback! We appreciate it.'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to submit feedback: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback. Please try again.'
            ], 500);
        }
    }

    /**
     * Get feedback list from log file (for admin)
     */
    public function feedbackList()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return response()->json([]);
            }
            
            $content = file_get_contents($logFile);
            $feedbackEntries = [];
            
            // Parse log entries for "User Feedback Received"
            preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*User Feedback Received.*?({.*?})\s*$/m', $content, $matches, PREG_SET_ORDER);
            
            foreach ($matches as $match) {
                $timestamp = $match[1];
                $jsonData = json_decode($match[2], true);
                
                if ($jsonData) {
                    $feedbackEntries[] = array_merge($jsonData, ['timestamp' => $timestamp]);
                }
            }
            
            // Return most recent first
            return response()->json(array_reverse($feedbackEntries));
            
        } catch (\Exception $e) {
            Log::error('Failed to read feedback: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * View feedback page (admin)
     */
    public function feedbackPage()
    {
        return view('admin.feedback');
    }

    /**
     * Get notification statistics (for admin)
     */
    public function stats()
    {
        $stats = [
            'total_subscriptions' => NotificationSubscription::count(),
            'active_subscriptions' => NotificationSubscription::active()->count(),
            'inactive_subscriptions' => NotificationSubscription::where('is_active', false)->count(),
            'subscriptions_today' => NotificationSubscription::whereDate('created_at', today())->count(),
            'subscriptions_this_week' => NotificationSubscription::where('created_at', '>=', now()->subWeek())->count(),
        ];

        return response()->json($stats);
    }
}
