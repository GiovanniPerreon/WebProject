'use strict';

/**
 * Global Comment Notifications Module
 * Polls for new comments on user's posts across all pages
 * Shows notifications when NOT viewing the specific post
 */

const GlobalCommentNotifications = {
    pollingInterval: null,
    pollingFrequency: 5000, // 5 seconds (faster for better UX)
    lastCheckTime: null,
    storageKey: 'commentNotificationLastCheck',

    /**
     * Initialize global comment notifications
     */
    async init() {
        // Only run if user is logged in
        if (!this.isUserLoggedIn()) {
            console.log('User not logged in, skipping comment notifications');
            return;
        }

        // Initialize last check time from localStorage or server
        await this.initializeLastCheckTime();

        // Start polling
        this.startPolling();
        console.log('Global comment notifications started');
    },

    /**
     * Initialize the last check time
     * Uses localStorage to persist across page loads
     */
    async initializeLastCheckTime() {
        // Try to get from localStorage first
        const storedTime = localStorage.getItem(this.storageKey);

        if (storedTime) {
            this.lastCheckTime = storedTime;
            console.log('Loaded last check time from storage:', storedTime);
        } else {
            // First time - set to 30 seconds ago to catch recent comments
            const now = new Date();
            now.setSeconds(now.getSeconds() - 30);
            this.lastCheckTime = now.toISOString().slice(0, 19).replace('T', ' ');

            // Save to localStorage
            localStorage.setItem(this.storageKey, this.lastCheckTime);
            console.log('Initialized last check time:', this.lastCheckTime);
        }
    },

    /**
     * Check if user is logged in (basic check via DOM)
     */
    isUserLoggedIn() {
        // Check if any login indicator exists
        const logoutLink = document.querySelector('a[href="logout.php"]');
        const messagesLink = document.querySelector('a[href="messaggi.php"]');

        return logoutLink !== null || messagesLink !== null;
    },

    /**
     * Start polling for new comments
     */
    startPolling() {
        this.pollingInterval = setInterval(async () => {
            await this.checkForNewComments();
        }, this.pollingFrequency);
    },

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            console.log('Global comment notifications stopped');
        }
    },

    /**
     * Check for new comments on user's posts
     */
    async checkForNewComments() {
        try {
            const response = await fetch(`api-comments.php?action=getMyNewComments&since=${encodeURIComponent(this.lastCheckTime)}`);
            const data = await response.json();

            console.log('Global comment poll response:', data);

            if (data.success && data.newComments && data.newComments.length > 0) {
                console.log(`Found ${data.newComments.length} new comments on your posts`);

                // Update last check time and save to localStorage
                this.lastCheckTime = data.currentTime;
                localStorage.setItem(this.storageKey, this.lastCheckTime);

                // Show notification
                this.showNotification(data.newComments);
            } else if (data.success && data.currentTime) {
                // Update time even if no new comments and save to localStorage
                this.lastCheckTime = data.currentTime;
                localStorage.setItem(this.storageKey, this.lastCheckTime);
                console.log('No new comments, time updated to:', data.currentTime);
            }
        } catch (err) {
            console.error('Global comment notification polling error:', err);
        }
    },

    /**
     * Show notification for new comments
     */
    showNotification(newComments) {
        if (!window.Notifications) {
            console.log(`New comments on your posts: ${newComments.length}`);
            return;
        }

        // Check if we're currently viewing a post page
        const urlParams = new URLSearchParams(window.location.search);
        const currentPostId = window.location.pathname.includes('post.php')
            ? urlParams.get('id')
            : null;

        // Group comments by post
        const commentsByPost = {};
        newComments.forEach(comment => {
            // Skip notification if we're currently viewing this post
            if (currentPostId && comment.idpost == currentPostId) {
                console.log(`Skipping notification for post ${comment.idpost} - user is viewing it`);
                return;
            }

            if (!commentsByPost[comment.idpost]) {
                commentsByPost[comment.idpost] = {
                    postTitle: comment.titolopost,
                    count: 0
                };
            }
            commentsByPost[comment.idpost].count++;
        });

        // Show notification for each post with new comments (except the one being viewed)
        Object.keys(commentsByPost).forEach(postId => {
            const post = commentsByPost[postId];
            const message = post.count === 1
                ? `💬 Nuovo commento su "${this.truncate(post.postTitle, 30)}"`
                : `💬 ${post.count} nuovi commenti su "${this.truncate(post.postTitle, 30)}"`;

            Notifications.info(message);
        });
    },

    /**
     * Truncate text to specified length
     */
    truncate(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    GlobalCommentNotifications.init();
});

// Stop polling when user leaves page
window.addEventListener('beforeunload', () => {
    GlobalCommentNotifications.stopPolling();
});

// Make globally available
window.GlobalCommentNotifications = GlobalCommentNotifications;
