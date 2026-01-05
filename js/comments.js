'use strict';

/**
 * Comments Module
 * Handles AJAX comment submission without page reloads
 */

const CommentsManager = {
    pollingInterval: null,
    pollingFrequency: 5000, // 5 seconds
    lastCommentId: null,
    currentPostId: null,

    /**
     * Initialize the comments system
     */
    init() {
        console.log('Comments Manager initialized');
        this.attachFormListener();
        this.initializePolling();
    },

    /**
     * Initialize comment polling for real-time updates
     */
    initializePolling() {
        // Get post ID from the page
        const form = document.querySelector('form[action="processa-commento.php"]');
        if (form) {
            this.currentPostId = form.querySelector('input[name="idpost"]').value;
        } else {
            // No form means user not logged in, but we can still poll for new comments
            const commentsSection = document.querySelector('section h4');
            if (commentsSection) {
                // Try to get post ID from URL
                const urlParams = new URLSearchParams(window.location.search);
                this.currentPostId = urlParams.get('id');
            }
        }

        if (!this.currentPostId) {
            return;
        }

        // Get the last comment ID currently on the page
        const comments = document.querySelectorAll('.comment[id^="comment-"]');
        if (comments.length > 0) {
            const lastComment = comments[comments.length - 1];
            this.lastCommentId = parseInt(lastComment.id.replace('comment-', ''));
        } else {
            this.lastCommentId = 0;
        }

        // Start polling
        this.startPolling();
    },

    /**
     * Start polling for new comments
     */
    startPolling() {
        this.pollingInterval = setInterval(async () => {
            await this.checkForNewComments();
        }, this.pollingFrequency);
        console.log('Comment polling started');
    },

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            console.log('Comment polling stopped');
        }
    },

    /**
     * Check for new comments
     */
    async checkForNewComments() {
        if (!this.currentPostId) return;

        try {
            const response = await fetch(`api-comments.php?action=getNewComments&idpost=${this.currentPostId}&since=${this.lastCommentId}`);
            const data = await response.json();

            if (data.success && data.comments && data.comments.length > 0) {
                // Add new comments to the page
                data.comments.forEach(comment => {
                    this.appendComment(comment, data.isAdmin, data.currentUserId, true);
                    this.lastCommentId = Math.max(this.lastCommentId, comment.idcommento);
                });

                // Don't show notification here - global comment notification system handles this
                // This local polling is just for real-time UI updates on the post page
            }
        } catch (err) {
            console.error('Comment polling error:', err);
        }
    },

    /**
     * Attach submit listener to comment form
     */
    attachFormListener() {
        const commentForm = document.querySelector('form[action="processa-commento.php"]');

        if (!commentForm) {
            return; // No comment form on this page
        }

        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.submitComment(commentForm);
        });
    },

    /**
     * Submit comment via AJAX
     */
    async submitComment(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        const textarea = form.querySelector('textarea[name="testocommento"]');
        const idpost = form.querySelector('input[name="idpost"]').value;
        const testocommento = textarea.value.trim();

        // Validate
        if (!testocommento) {
            if (window.Notifications) {
                Notifications.error('Il commento non può essere vuoto');
            }
            return;
        }

        // Disable button to prevent double submission
        submitButton.disabled = true;
        submitButton.textContent = 'Invio...';

        try {
            const formData = new FormData();
            formData.append('action', 'addComment');
            formData.append('idpost', idpost);
            formData.append('testocommento', testocommento);

            const response = await fetch('api-comments.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Add comment to the page (fromPolling = false, so no notification)
                this.appendComment(data.comment, data.isAdmin, data.currentUserId, false);

                // Clear textarea
                textarea.value = '';

                // Update lastCommentId to prevent duplicate from polling
                this.lastCommentId = data.comment.idcommento;

                // Scroll to the new comment
                const newComment = document.getElementById(`comment-${data.comment.idcommento}`);
                if (newComment) {
                    newComment.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            } else {
                // Show error notification
                if (window.Notifications) {
                    Notifications.error(data.error || 'Errore nell\'invio del commento');
                } else {
                    alert(data.error || 'Errore nell\'invio del commento');
                }
            }
        } catch (err) {
            console.error('Comment submission error:', err);
            if (window.Notifications) {
                Notifications.error('Errore di rete. Riprova.');
            } else {
                alert('Errore di rete. Riprova.');
            }
        } finally {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.textContent = 'Invia';
        }
    },

    /**
     * Append new comment to the comments section
     * @param {object} comment - The comment data
     * @param {boolean} isAdmin - Whether current user is admin
     * @param {number} currentUserId - Current user ID
     * @param {boolean} fromPolling - Whether comment is from polling (not manual submission)
     */
    appendComment(comment, isAdmin, currentUserId, fromPolling = false) {
        const commentsSection = document.querySelector('section h4');

        if (!commentsSection) {
            return;
        }

        // Find the parent section
        const section = commentsSection.parentElement;

        // Check if there's a "Nessun commento" message to remove
        const noCommentsMsg = section.querySelector('article p');
        if (noCommentsMsg && noCommentsMsg.textContent === 'Nessun commento') {
            noCommentsMsg.parentElement.remove();
        }

        // Find where to insert (before the form or "login to comment" message)
        const form = section.querySelector('form');
        const loginMessage = section.querySelector('.login-message');
        const insertBefore = form || loginMessage;

        // Create comment HTML
        const commentHTML = this.renderComment(comment, isAdmin, currentUserId);

        // Insert before the form
        if (insertBefore) {
            insertBefore.insertAdjacentHTML('beforebegin', commentHTML);
        } else {
            section.insertAdjacentHTML('beforeend', commentHTML);
        }

        // Add animation
        const newComment = document.getElementById(`comment-${comment.idcommento}`);
        if (newComment) {
            newComment.style.animation = 'commentAdded 0.5s ease';
        }
    },

    /**
     * Render comment HTML
     */
    renderComment(comment, isAdmin, currentUserId) {
        const commenterId = comment.idutente || null;
        const commenterName = this.escapeHtml(comment.nomeautore);
        const commenterIsAdmin = comment.amministratore ? true : false;
        const commenterBadge = commenterIsAdmin ? ' <span class="admin-badge" title="Amministratore">👑</span>' : '';
        const commentText = this.escapeHtml(comment.testocommento);
        const commentDate = comment.datacommento;

        let authorHTML = '';
        if (commenterId) {
            authorHTML = `<a href="profilo.php?id=${commenterId}" class="author-link">${commenterName}</a>${commenterBadge}`;
        } else {
            authorHTML = `${commenterName}${commenterBadge}`;
        }

        // Report button (only if logged in - we know user is logged in if they just posted)
        const reportBtn = `<button class="segnala-btn icon-only" title="Segnala commento" aria-label="Segnala commento" data-idcommento="${comment.idcommento}" data-idpost="${comment.post}">⚠️</button>`;

        // Delete button (only for admins)
        const deleteBtn = isAdmin ? `<button class="admin-delete-comment-btn" onclick="deleteComment(${comment.idcommento})">🗑️</button>` : '';

        return `
        <article class="comment" id="comment-${comment.idcommento}">
            <p>
                <strong>${authorHTML}</strong>:
                ${commentText}
                <small>${commentDate}</small>
                ${reportBtn}
                ${deleteBtn}
            </p>
        </article>`;
    },

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on a post page (has comment form or comments section)
    if (document.querySelector('section h4')) {
        const heading = document.querySelector('section h4');
        if (heading && heading.textContent === 'Commenti') {
            CommentsManager.init();
        }
    }
});

// Stop polling when user leaves page
window.addEventListener('beforeunload', () => {
    CommentsManager.stopPolling();
});

// Make globally available
window.CommentsManager = CommentsManager;
