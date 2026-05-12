/**
 * Web Push Notifications Manager
 * Handles notification subscription, unsubscription, and feedback
 */

class WebNotificationManager {
    constructor() {
        this.vapidPublicKey = document.querySelector('meta[name="vapid-public-key"]')?.content;
        this.isSupported = 'serviceWorker' in navigator && 'PushManager' in window;
        this.subscribeBtn = document.getElementById('notificationToggle');
        this.feedbackBtn = document.getElementById('feedbackBtn');
        this.init();
    }

    async init() {
        if (!this.isSupported) {
            console.log('Push notifications not supported');
            this.hideNotificationUI();
            return;
        }

        console.log('WebNotificationManager initialized successfully');
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Check current subscription status
        await this.updateUI();
        
        // Auto-request permission on first visit
        await this.autoRequestPermission();
    }
    
    async autoRequestPermission() {
        console.log('autoRequestPermission called');
        console.log('Current permission:', Notification.permission);
        
        // If already granted, auto-subscribe
        if (Notification.permission === 'granted') {
            const isSubscribed = localStorage.getItem('notifications_enabled') === 'true';
            if (!isSubscribed) {
                console.log('Permission already granted, auto-subscribing...');
                await this.subscribeSimple();
            }
            return;
        }
        
        // If denied, skip (browser won't show dialog anyway)
        if (Notification.permission === 'denied') {
            console.log('Notifications are blocked. User needs to enable in browser settings.');
            return;
        }
        
        // Always ask if permission is default (not decided yet)
        if (Notification.permission === 'default') {
            console.log('Will request permission in 2 seconds...');
            // Wait 2 seconds after page load, then ask
            setTimeout(async () => {
                try {
                    console.log('Requesting notification permission now...');
                    const permission = await Notification.requestPermission();
                    console.log('Permission result:', permission);
                    
                    if (permission === 'granted') {
                        console.log('Permission granted! Auto-subscribing...');
                        // Auto-subscribe
                        await this.subscribeSimple();
                    } else {
                        console.log('Permission denied or dismissed');
                    }
                } catch (error) {
                    console.error('Auto permission request failed:', error);
                }
            }, 2000);
        }
    }

    setupEventListeners() {
        if (this.subscribeBtn) {
            this.subscribeBtn.addEventListener('click', () => {
                console.log('Notification button clicked');
                this.toggleSubscription();
            });
        } else {
            console.error('Notification button not found');
        }

        if (this.feedbackBtn) {
            this.feedbackBtn.addEventListener('click', () => {
                console.log('Feedback button clicked');
                this.showFeedbackModal();
            });
        } else {
            console.error('Feedback button not found');
        }
    }

    async toggleSubscription() {
        console.log('Toggle subscription called');
        
        try {
            const permission = await Notification.requestPermission();
            console.log('Permission:', permission);
            
            if (permission !== 'granted') {
                this.showToast('Please allow notifications in your browser settings', 'warning');
                this.showLoading(false);
                return;
            }

            // For now, just simulate subscription without actual push
            const isSubscribed = localStorage.getItem('notifications_enabled') === 'true';
            console.log('Currently subscribed:', isSubscribed);
            
            if (isSubscribed) {
                await this.unsubscribeSimple();
            } else {
                await this.subscribeSimple();
            }
        } catch (error) {
            console.error('Toggle subscription error:', error);
            this.showToast('Error: ' + error.message, 'error');
            this.showLoading(false);
        }
    }

    async subscribeSimple() {
        try {
            this.showLoading(true);

            // Store in localStorage for now
            localStorage.setItem('notifications_enabled', 'true');

            const response = await fetch('/notifications/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    endpoint: 'simple-' + Date.now(),
                    keys: {
                        p256dh: 'simple-key',
                        auth: 'simple-auth'
                    }
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showToast('🔔 Notifications enabled! You\'ll get updates on new jobs', 'success');
                await this.updateUI();
            } else {
                throw new Error(data.message);
            }

        } catch (error) {
            console.error('Subscription failed:', error);
            this.showToast('Failed to enable notifications', 'error');
            localStorage.removeItem('notifications_enabled');
        } finally {
            this.showLoading(false);
        }
    }

    async unsubscribeSimple() {
        try {
            this.showLoading(true);

            localStorage.removeItem('notifications_enabled');
            
            this.showToast('🔕 Notifications disabled', 'info');
            await this.updateUI();

        } catch (error) {
            console.error('Unsubscription failed:', error);
            this.showToast('Failed to disable notifications', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    async updateUI() {
        if (!this.subscribeBtn) return;

        const isSubscribed = localStorage.getItem('notifications_enabled') === 'true';

        if (isSubscribed) {
            this.subscribeBtn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg><span>Notifications ON</span>';
            this.subscribeBtn.style.backgroundColor = '#16a34a';
        } else {
            this.subscribeBtn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg><span>Enable Notifications</span>';
            this.subscribeBtn.style.backgroundColor = '#2563eb';
        }
    }

    showFeedbackModal() {
        const modal = document.getElementById('feedbackModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    hideFeedbackModal() {
        const modal = document.getElementById('feedbackModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async submitFeedback(formData) {
        console.log('submitFeedback called with:', formData);
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF token element:', csrfToken);
            console.log('CSRF token value:', csrfToken?.content);
            
            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }
            
            console.log('Sending feedback to /feedback...');
            const response = await fetch('/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content
                },
                body: JSON.stringify(formData)
            });

            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                this.showToast(data.message, 'success');
                this.hideFeedbackModal();
                document.getElementById('feedbackForm').reset();
            } else {
                throw new Error(data.message || 'Unknown error');
            }

        } catch (error) {
            console.error('Feedback submission failed:', error);
            this.showToast('Failed to submit feedback: ' + error.message, 'error');
        }
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.className = `fixed bottom-20 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl z-[10001] animate-slide-up`;
        toast.style.cssText = 'z-index: 10001 !important; min-width: 300px; font-size: 16px; font-weight: 600;';
        toast.innerHTML = `<div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px;">${icons[type]}</span><span>${message}</span></div>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            toast.style.transition = 'all 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    showLoading(show) {
        if (this.subscribeBtn) {
            this.subscribeBtn.disabled = show;
            if (show) {
                this.subscribeBtn.innerHTML = '<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" style="display: inline-block; width: 1.25rem; height: 1.25rem;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span style="margin-left: 0.5rem;">Loading...</span>';
            }
        }
    }

    hideNotificationUI() {
        if (this.subscribeBtn) {
            this.subscribeBtn.style.display = 'none';
        }
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
}

// Initialize immediately - no waiting for DOM
console.log('web-notifications.js starting...');

try {
    window.notificationManager = new WebNotificationManager();
    console.log('WebNotificationManager created immediately');
} catch (error) {
    console.error('Failed to create WebNotificationManager:', error);
}

// Also try after DOM loads as backup
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM loaded, initializing WebNotificationManager...');
        if (!window.notificationManager) {
            try {
                window.notificationManager = new WebNotificationManager();
                console.log('WebNotificationManager initialized on DOM ready');
            } catch (error) {
                console.error('Failed on DOM ready:', error);
            }
        }
    });
}

console.log('web-notifications.js loaded successfully');
