'use strict';

/**
 * Post Actions Module
 * Handles share, DM request, and pin functionality
 *
 * DEPENDENCIES:
 * - main.js (SpottedApp) for copyToClipboard utility
 * - notifications.js for toast messages
 */

const PostActions = {
    /**
     * Share a post by copying its link to clipboard
     * NOW USES: SpottedApp.utils.copyToClipboard()
     * @param {number} postId - The ID of the post to share
     */
    async sharePost(postId) {
        const url = `${window.location.origin}${window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'))}/post.php?id=${postId}`;

        // Use SpottedApp utility if available
        if (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.copyToClipboard) {
            try {
                await window.SpottedApp.utils.copyToClipboard(url);
                Notifications.success('Link copiato negli appunti!');
            } catch (err) {
                console.error('Clipboard error:', err);
                Notifications.error('Impossibile copiare il link');
            }
        } else {
            // Fallback if SpottedApp not available
            console.warn('SpottedApp.utils.copyToClipboard not available, using fallback');
            this.fallbackCopyToClipboard(url);
        }
    },

    /**
     * Fallback method to copy text to clipboard
     * DEPRECATED: Prefer SpottedApp.utils.copyToClipboard()
     * @param {string} text - Text to copy
     */
    fallbackCopyToClipboard(text) {
        // Try modern API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    Notifications.success('Link copiato negli appunti!');
                })
                .catch(err => {
                    console.error('Clipboard API failed:', err);
                    this.execCommandFallback(text);
                });
        } else {
            this.execCommandFallback(text);
        }
    },

    /**
     * execCommand fallback for very old browsers
     * @param {string} text - Text to copy
     */
    execCommandFallback(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                Notifications.success('Link copiato negli appunti!');
            } else {
                Notifications.error('Impossibile copiare il link');
            }
        } catch (err) {
            console.error('execCommand copy failed:', err);
            Notifications.error('Impossibile copiare il link');
        }

        document.body.removeChild(textArea);
    },

    /**
     * Pin or unpin a post (admin only)
     * @param {number} postId - The ID of the post to pin/unpin
     * @param {boolean} shouldPin - Whether to pin (true) or unpin (false)
     */
    async pinPost(postId, shouldPin) {
        const formData = new FormData();
        formData.append('idpost', postId);
        formData.append('action', shouldPin ? 'pin' : 'unpin');

        try {
            const response = await fetch('api-pin.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Operazione completata');
                // Update UI
                this.updatePinButton(postId, data.pinned);
                // Optionally reload to show pinned post at top
                setTimeout(() => location.reload(), 1000);
            } else {
                Notifications.error(data.message || 'Errore durante l\'operazione');
            }
        } catch (err) {
            console.error('Pin error:', err);
            Notifications.error('Errore di connessione');
        }
    },

    /**
     * Update the pin button UI
     * @param {number} postId - The ID of the post
     * @param {boolean} isPinned - Whether the post is pinned
     */
    updatePinButton(postId, isPinned) {
        const button = document.querySelector(`[data-post-id="${postId}"].pin-btn`);
        if (button) {
            button.textContent = isPinned ? '📌 Unpinned' : '📌 Pin';
            button.dataset.pinned = isPinned;
            if (isPinned) {
                button.classList.add('pinned');
            } else {
                button.classList.remove('pinned');
            }
        }
    },

    /**
     * Request DM with post author
     * @param {number} postId - The ID of the post
     */
    requestDM(postId) {
        // TODO: Implement DM request modal
        // This will be implemented when the backend is ready
        Notifications.info('Funzionalità in arrivo!');
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('📤 Post Actions initialized');

    // Attach event listeners to share buttons
    document.querySelectorAll('.condividi-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = e.currentTarget.dataset.postId ||
                          e.currentTarget.closest('article')?.querySelector('[data-post-id]')?.dataset.postId ||
                          new URLSearchParams(window.location.search).get('id');

            if (postId) {
                PostActions.sharePost(postId);
            } else {
                Notifications.error('ID post non trovato');
            }
        });
    });

    // Attach event listeners to pin buttons
    document.querySelectorAll('.pin-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = e.currentTarget.dataset.postId;
            const isPinned = e.currentTarget.dataset.pinned === 'true';

            if (postId) {
                PostActions.pinPost(postId, !isPinned);
            } else {
                Notifications.error('ID post non trovato');
            }
        });
    });

    // Attach event listeners to DM request buttons
    document.querySelectorAll('.dm-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const postId = e.currentTarget.dataset.postId;

            if (postId) {
                PostActions.requestDM(postId);
            }
        });
    });
});

// Make PostActions available globally
window.PostActions = PostActions;
