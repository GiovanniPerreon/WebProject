'use strict';

/**
 * Message Polling Module
 * Polls for new messages on all pages (except messages page)
 * Updates navigation badge and shows toast notifications
 */

const GlobalMessageNotifications = {
    pollingInterval: null,
    pollingFrequency: 10000, // 10 seconds (less aggressive than on messages page)
    lastUnreadCount: 0,
    isOnMessagesPage: false,

    /**
     * Initialize global message notifications
     */
    init() {
        // Check if we're on the messages page
        this.isOnMessagesPage = window.location.pathname.includes('messaggi.php');

        // Only run if user is logged in (check for badge element)
        const badge = document.getElementById('unread-messages-badge');
        if (!badge) {
            console.log('User not logged in, skipping message notifications');
            return;
        }

        // Get initial unread count
        this.lastUnreadCount = parseInt(badge.textContent) || 0;

        // Start polling ONLY if NOT on messages page
        // (messages page has its own polling system)
        if (!this.isOnMessagesPage) {
            this.startPolling();
            console.log('Global message notifications started');
        } else {
            console.log('On messages page, using MessagesManager polling instead');
        }
    },

    /**
     * Start polling for new messages
     */
    startPolling() {
        this.pollingInterval = setInterval(async () => {
            await this.checkForNewMessages();
        }, this.pollingFrequency);
    },

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    },

    /**
     * Check for new messages
     */
    async checkForNewMessages() {
        try {
            const response = await fetch('api-messages.php?action=getUnreadCount');
            const data = await response.json();

            if (data.success) {
                const newCount = data.count || 0;

                // Update badge
                this.updateBadge(newCount);

                // If count increased, show notification
                if (newCount > this.lastUnreadCount) {
                    const newMessages = newCount - this.lastUnreadCount;
                    this.showNotification(newMessages);
                }

                this.lastUnreadCount = newCount;
            }
        } catch (err) {
            console.error('Global message notification polling error:', err);
        }
    },

    /**
     * Update the navigation badge
     */
    updateBadge(count) {
        const badge = document.getElementById('unread-messages-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }
    },

    /**
     * Show notification for new message
     */
    showNotification(count) {
        // Use the global Notifications system if available
        if (window.Notifications) {
            const message = count === 1
                ? '📬 Hai un nuovo messaggio!'
                : `📬 Hai ${count} nuovi messaggi!`;

            Notifications.info(message);
        } else {
            // Fallback to console
            console.log(`New message notification: ${count} message(s)`);
        }

        // Optional: Browser notification (requires permission)
        this.showBrowserNotification(count);
    },

    /**
     * Show browser notification (optional)
     */
    showBrowserNotification(count) {
        // Check if browser supports notifications
        if (!('Notification' in window)) {
            return;
        }

        // Check if permission is granted
        if (Notification.permission === 'granted') {
            const message = count === 1
                ? 'Hai un nuovo messaggio!'
                : `Hai ${count} nuovi messaggi!`;

            new Notification('Spotted Unibo Cesena', {
                body: message,
                icon: './upload/default-avatar.png', // Use a site icon
                badge: './upload/default-avatar.png',
                tag: 'new-message' // Prevents duplicate notifications
            });
        }
        // Don't request permission automatically - too intrusive
        // User can enable this manually if desired
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    GlobalMessageNotifications.init();
});

// Stop polling when user leaves page
window.addEventListener('beforeunload', () => {
    GlobalMessageNotifications.stopPolling();
});

// Make globally available
window.GlobalMessageNotifications = GlobalMessageNotifications;
