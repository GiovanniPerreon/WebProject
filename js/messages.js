'use strict';

/**
 * Messages Module
 * Handles real-time messaging without page reloads
 * Features: AJAX conversation switching, message sending, auto-refresh
 *
 * DEPENDENCIES:
 * - main.js (SpottedApp) for utilities
 * - notifications.js for toast messages
 *
 * INTEGRATIONS:
 * - XSS prevention (uses SpottedApp.utils.escapeHtml)
 * - Smooth scrolling (uses SpottedApp.utils.scrollToElement)
 */

const MessagesManager = {
    currentUserId: null,
    pollingInterval: null,
    conversationListPollingInterval: null,
    pollingFrequency: 3000, // 3 seconds
    conversationListPollingFrequency: 5000, // 5 seconds for conversation list
    isPolling: false,
    isConversationListPolling: false,
    lastMessageId: null,

    /**
     * Initialize the messages system
     */
    init() {
        console.log('Messages Manager initialized');

        // Check if SpottedApp is available
        if (!window.SpottedApp) {
            console.warn('MessagesManager: SpottedApp not loaded. Using fallback utilities.');
        }

        // Attach event listeners
        this.attachConversationListeners();
        this.attachMessageFormListener();

        // Get current conversation from URL
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('user');

        if (userId) {
            this.currentUserId = parseInt(userId);
            this.scrollToBottom();
            this.markAsRead(userId);
            this.startPolling();
        }

        // ALWAYS start conversation list polling (updates badges)
        this.startConversationListPolling();
    },

    /**
     * Attach click listeners to conversation items
     */
    attachConversationListeners() {
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = this.extractUserIdFromUrl(item.href);

                if (userId) {
                    this.loadConversation(userId);
                }
            });
        });
    },

    /**
     * Attach submit listener to message form
     */
    attachMessageFormListener() {
        const form = document.getElementById('message-form');

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage(form);
            });
        }
    },

    /**
     * Extract user ID from conversation URL
     */
    extractUserIdFromUrl(url) {
        const urlObj = new URL(url);
        return urlObj.searchParams.get('user');
    },

    /**
     * Load a conversation via AJAX
     * @param {number} userId - The ID of the user to chat with
     */
    async loadConversation(userId) {
        // Stop current polling
        this.stopPolling();

        // Update current user
        this.currentUserId = parseInt(userId);

        // Update URL without reload
        const newUrl = `messaggi.php?user=${userId}`;
        window.history.pushState({ userId }, '', newUrl);

        // Show loading state
        this.showChatLoading();

        try {
            const response = await fetch(`api-messages.php?action=getConversation&user=${userId}`);
            const data = await response.json();

            if (data.success) {
                this.renderConversation(data.user, data.messages);
                this.updateActiveConversation(userId);
                this.markAsRead(userId);
                this.startPolling();
            } else {
                Notifications.error(data.error || 'Errore nel caricamento della conversazione');
            }
        } catch (err) {
            console.error('Load conversation error:', err);
            Notifications.error('Errore di connessione');
        }
    },

    /**
     * Show loading state in chat area
     */
    showChatLoading() {
        const chatArea = document.querySelector('.chat-area');
        if (chatArea) {
            chatArea.innerHTML = `
                <div class="loading-state">
                    <p>Caricamento...</p>
                </div>
            `;
        }
    },

    /**
     * Render conversation in chat area
     * NOW USES: SpottedApp.utils.escapeHtml() for XSS prevention
     */
    renderConversation(user, messages) {
        const chatArea = document.querySelector('.chat-area');
        if (!chatArea) return;

        // Get current user ID from session (injected by PHP)
        const currentUserIdSession = window.currentUserId || null;

        // Track last message ID for polling
        if (messages.length > 0) {
            this.lastMessageId = Math.max(...messages.map(m => m.idmessaggio));
        }

        // Use SpottedApp escapeHtml if available, otherwise use fallback
        const escapeHtml = (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.escapeHtml)
            ? window.SpottedApp.utils.escapeHtml
            : this.escapeHtmlFallback;

        chatArea.innerHTML = `
            <!-- Chat Header -->
            <div class="chat-header">
                <img src="./upload/${user.imgprofilo}"
                     alt="${escapeHtml(user.nome)}"
                     class="chat-avatar">
                <div>
                    <strong>${escapeHtml(user.nome)}</strong>
                    <small>@${escapeHtml(user.username)}</small>
                </div>
            </div>

            <!-- Messages -->
            <div class="messages-box" id="messages-box">
                ${messages.map(msg => this.renderMessage(msg, currentUserIdSession)).join('')}
            </div>

            <!-- Message Input -->
            <form class="message-form" id="message-form">
                <input type="hidden" name="action" value="sendMessage">
                <input type="hidden" name="destinatario" value="${user.idutente}">
                <textarea name="messaggio" placeholder="Scrivi un messaggio..." required maxlength="1000"></textarea>
                <button type="submit">Invia</button>
            </form>
        `;

        // Reattach form listener
        this.attachMessageFormListener();

        // Scroll to bottom
        this.scrollToBottom();
    },

    /**
     * Render a single message
     * NOW USES: SpottedApp.utils.escapeHtml() for XSS prevention
     */
    renderMessage(msg, currentUserId) {
        const isSent = msg.mittente == currentUserId;
        const time = this.formatTime(msg.datamessaggio);

        // Use SpottedApp escapeHtml if available, otherwise use fallback
        const escapeHtml = (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.escapeHtml)
            ? window.SpottedApp.utils.escapeHtml
            : this.escapeHtmlFallback;

        return `
            <div class="message ${isSent ? 'sent' : 'received'}" data-message-id="${msg.idmessaggio}">
                <div class="message-content">
                    <p>${escapeHtml(msg.testomessaggio).replace(/\n/g, '<br>')}</p>
                    <small class="message-timestamp">${time}</small>
                </div>
            </div>
        `;
    },

    /**
     * Send a message via AJAX
     */
    async sendMessage(form) {
        const formData = new FormData(form);
        const textarea = form.querySelector('textarea[name="messaggio"]');
        const messageText = textarea.value.trim();

        if (!messageText) return;

        // Disable form during send
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Invio...';

        // Optimistic UI update - add message immediately
        this.addOptimisticMessage(messageText);

        // Clear textarea
        textarea.value = '';

        try {
            const response = await fetch('api-messages.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Replace optimistic message with real one
                this.replaceOptimisticMessage(data.message);
                this.scrollToBottom();

                // Update conversation list (move to top, update last message)
                this.updateConversationInList(this.currentUserId, messageText);
            } else {
                Notifications.error(data.error || 'Errore nell\'invio del messaggio');
                // Remove optimistic message on failure
                this.removeOptimisticMessage();
            }
        } catch (err) {
            console.error('Send message error:', err);
            Notifications.error('Errore di connessione');
            this.removeOptimisticMessage();
        } finally {
            // Re-enable form
            submitBtn.disabled = false;
            submitBtn.textContent = 'Invia';
            textarea.focus();
        }
    },

    /**
     * Add optimistic message (shown immediately before server confirms)
     * NOW USES: SpottedApp.utils.escapeHtml() for XSS prevention
     */
    addOptimisticMessage(text) {
        const messagesBox = document.getElementById('messages-box');
        if (!messagesBox) return;

        const now = new Date();
        const time = now.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });

        // Use SpottedApp escapeHtml if available, otherwise use fallback
        const escapeHtml = (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.escapeHtml)
            ? window.SpottedApp.utils.escapeHtml
            : this.escapeHtmlFallback;

        const messageHtml = `
            <div class="message sent optimistic" data-optimistic="true">
                <div class="message-content">
                    <p>${escapeHtml(text).replace(/\n/g, '<br>')}</p>
                    <small class="message-timestamp">${time}</small>
                </div>
            </div>
        `;

        messagesBox.insertAdjacentHTML('beforeend', messageHtml);
        this.scrollToBottom();
    },

    /**
     * Replace optimistic message with real message from server
     */
    replaceOptimisticMessage(message) {
        const optimisticMsg = document.querySelector('.message.optimistic');
        if (optimisticMsg && message) {
            // Remove optimistic class and add real message ID
            optimisticMsg.classList.remove('optimistic');
            optimisticMsg.dataset.messageId = message.idmessaggio;
            optimisticMsg.removeAttribute('data-optimistic');

            // Update last message ID for polling
            this.lastMessageId = message.idmessaggio;
        }
    },

    /**
     * Remove optimistic message (on error)
     */
    removeOptimisticMessage() {
        const optimisticMsg = document.querySelector('.message.optimistic');
        if (optimisticMsg) {
            optimisticMsg.remove();
        }
    },

    /**
     * Update active conversation highlight in sidebar
     */
    updateActiveConversation(userId) {
        // Remove active class from all
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('active');
        });

        // Add active class to selected
        const activeItem = document.querySelector(`.conversation-item[href*="user=${userId}"]`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
    },

    /**
     * Mark messages as read
     */
    async markAsRead(userId) {
        try {
            const formData = new FormData();
            formData.append('action', 'markAsRead');
            formData.append('user', userId);

            const response = await fetch('api-messages.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Update unread badge in conversation list
                this.updateUnreadBadge(userId, 0);

                // Update total unread count in navigation
                this.updateNavigationBadge();
            }
        } catch (err) {
            console.error('Mark as read error:', err);
        }
    },

    /**
     * Update unread badge for a conversation
     */
    updateUnreadBadge(userId, count) {
        const conversationItem = document.querySelector(`.conversation-item[href*="user=${userId}"]`);
        if (!conversationItem) return;

        const badge = conversationItem.querySelector('.unread-badge');

        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else {
                // Create badge if it doesn't exist
                const footer = conversationItem.querySelector('.conversation-footer');
                if (footer) {
                    footer.insertAdjacentHTML('beforeend', `<span class="unread-badge">${count}</span>`);
                }
            }
        } else {
            // Remove badge if count is 0
            if (badge) {
                badge.remove();
            }
        }
    },

    /**
     * Update conversation in sidebar list (move to top, update last message)
     */
    updateConversationInList(userId, lastMessage) {
        const conversationItem = document.querySelector(`.conversation-item[href*="user=${userId}"]`);
        if (!conversationItem) return;

        // Update last message text
        const lastMessageEl = conversationItem.querySelector('.last-message');
        if (lastMessageEl) {
            const truncated = lastMessage.length > 50 ? lastMessage.substring(0, 50) + '...' : lastMessage;
            lastMessageEl.textContent = truncated;
        }

        // Update timestamp
        const timeEl = conversationItem.querySelector('.message-time');
        if (timeEl) {
            const now = new Date();
            timeEl.textContent = now.toLocaleDateString('it-IT', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
        }

        // Move to top of list (optional - uncomment if desired)
        // const list = conversationItem.parentElement;
        // list.insertBefore(conversationItem, list.firstChild);
    },

    /**
     * Start polling for new messages
     */
    startPolling() {
        if (this.isPolling || !this.currentUserId) return;

        this.isPolling = true;

        this.pollingInterval = setInterval(async () => {
            await this.checkForNewMessages();
        }, this.pollingFrequency);

        console.log('Message polling started');
    },

    /**
     * Stop polling for new messages
     */
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            this.isPolling = false;
            console.log('Message polling stopped');
        }
    },

    /**
     * Check for new messages via AJAX
     */
    async checkForNewMessages() {
        if (!this.currentUserId) return;

        try {
            const url = `api-messages.php?action=getNewMessages&user=${this.currentUserId}&since=${this.lastMessageId || 0}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.success && data.messages && data.messages.length > 0) {
                // Append new messages
                this.appendNewMessages(data.messages);

                // Update last message ID
                this.lastMessageId = Math.max(...data.messages.map(m => m.idmessaggio));

                // Mark as read
                this.markAsRead(this.currentUserId);
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    },

    /**
     * Append new messages to chat
     */
    appendNewMessages(messages) {
        const messagesBox = document.getElementById('messages-box');
        if (!messagesBox) return;

        const currentUserIdSession = window.currentUserId || null;

        messages.forEach(msg => {
            // Check if message already exists
            if (document.querySelector(`[data-message-id="${msg.idmessaggio}"]`)) {
                return;
            }

            const messageHtml = this.renderMessage(msg, currentUserIdSession);
            messagesBox.insertAdjacentHTML('beforeend', messageHtml);
        });

        this.scrollToBottom();

        // DON'T show notification when inside the conversation
        // The global notification system handles this when user is NOT on messages page
    },

    /**
     * Update navigation badge (total unread count)
     */
    async updateNavigationBadge() {
        try {
            const response = await fetch('api-messages.php?action=getUnreadCount');
            const data = await response.json();

            if (data.success) {
                const badge = document.getElementById('unread-messages-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }
        } catch (err) {
            console.error('Update nav badge error:', err);
        }
    },

    /**
     * Scroll messages box to bottom
     * NOW USES: SpottedApp.utils.scrollToElement() for smooth scrolling
     */
    scrollToBottom() {
        setTimeout(() => {
            const messagesBox = document.getElementById('messages-box');
            if (!messagesBox) return;

            // Use SpottedApp scrollToElement if available
            if (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.scrollToElement) {
                // Get the last message element
                const lastMessage = messagesBox.lastElementChild;
                if (lastMessage) {
                    window.SpottedApp.utils.scrollToElement(lastMessage, 0);
                } else {
                    // Fallback: scroll container to bottom
                    messagesBox.scrollTop = messagesBox.scrollHeight;
                }
            } else {
                // Fallback: basic scroll to bottom
                messagesBox.scrollTop = messagesBox.scrollHeight;
            }
        }, 100);
    },

    /**
     * Format time from datetime string
     */
    formatTime(datetime) {
        const date = new Date(datetime);
        return date.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
    },

    /**
     * Fallback escapeHtml if SpottedApp not available
     * DEPRECATED: Prefer SpottedApp.utils.escapeHtml
     */
    escapeHtmlFallback(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    /**
     * Start polling for conversation list updates (badges, new conversations)
     */
    startConversationListPolling() {
        if (this.isConversationListPolling) return;

        this.isConversationListPolling = true;

        this.conversationListPollingInterval = setInterval(async () => {
            await this.updateConversationList();
        }, this.conversationListPollingFrequency);

        console.log('Conversation list polling started');
    },

    /**
     * Stop conversation list polling
     */
    stopConversationListPolling() {
        if (this.conversationListPollingInterval) {
            clearInterval(this.conversationListPollingInterval);
            this.conversationListPollingInterval = null;
            this.isConversationListPolling = false;
            console.log('Conversation list polling stopped');
        }
    },

    /**
     * Update conversation list (fetch latest data and update badges)
     */
    async updateConversationList() {
        try {
            const response = await fetch('api-messages.php?action=getConversationList');
            const data = await response.json();

            if (data.success && data.conversations) {
                // Update each conversation in the sidebar
                data.conversations.forEach(conv => {
                    this.updateConversationItem(conv);
                });

                // Update total unread count in navigation
                this.updateNavigationBadge();
            }
        } catch (err) {
            console.error('Conversation list update error:', err);
        }
    },

    /**
     * Update a single conversation item in the sidebar
     */
    updateConversationItem(conv) {
        let conversationItem = document.querySelector(`.conversation-item[href*="user=${conv.idutente}"]`);

        if (!conversationItem) {
            // Conversation doesn't exist in sidebar yet - create it dynamically
            this.createNewConversationItem(conv);
            return;
        }

        // Update last message text
        const lastMessageEl = conversationItem.querySelector('.last-message');
        if (lastMessageEl && conv.ultimo_messaggio) {
            const truncated = conv.ultimo_messaggio.length > 50
                ? conv.ultimo_messaggio.substring(0, 50) + '...'
                : conv.ultimo_messaggio;
            lastMessageEl.textContent = truncated;
        }

        // Update timestamp
        const timeEl = conversationItem.querySelector('.message-time');
        if (timeEl && conv.data_ultimo_messaggio) {
            const date = new Date(conv.data_ultimo_messaggio);
            timeEl.textContent = date.toLocaleDateString('it-IT', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Update unread badge
        this.updateUnreadBadge(conv.idutente, parseInt(conv.non_letti) || 0);
    },

    /**
     * Create a new conversation item in the sidebar when someone new messages you
     */
    createNewConversationItem(conv) {
        const conversationsList = document.querySelector('.conversations-list');
        if (!conversationsList) return;

        // Check for empty message placeholder
        const emptyMessage = conversationsList.querySelector('.empty-message');
        if (emptyMessage) {
            emptyMessage.remove();
        }

        // Use SpottedApp escapeHtml if available, otherwise use fallback
        const escapeHtml = (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.escapeHtml)
            ? window.SpottedApp.utils.escapeHtml
            : this.escapeHtmlFallback;

        // Format the timestamp
        const date = new Date(conv.data_ultimo_messaggio);
        const formattedTime = date.toLocaleDateString('it-IT', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Truncate last message
        const truncatedMessage = conv.ultimo_messaggio.length > 50
            ? conv.ultimo_messaggio.substring(0, 50) + '...'
            : conv.ultimo_messaggio;

        // Create the conversation item HTML
        const conversationHtml = `
            <a href="messaggi.php?user=${conv.idutente}" class="conversation-item">
                <img src="./upload/${escapeHtml(conv.imgprofilo)}" alt="${escapeHtml(conv.nome)}" class="conversation-avatar">
                <div class="conversation-info">
                    <div class="conversation-header">
                        <strong class="conversation-name">${escapeHtml(conv.nome)}</strong>
                        <small class="message-time">${formattedTime}</small>
                    </div>
                    <div class="conversation-footer">
                        <p class="last-message">${escapeHtml(truncatedMessage)}</p>
                        ${parseInt(conv.non_letti) > 0 ? `<span class="unread-badge">${conv.non_letti}</span>` : ''}
                    </div>
                </div>
            </a>
        `;

        // Insert at the top of the list (after h3)
        const heading = conversationsList.querySelector('h3');
        if (heading && heading.nextElementSibling) {
            heading.nextElementSibling.insertAdjacentHTML('beforebegin', conversationHtml);
        } else if (heading) {
            heading.insertAdjacentHTML('afterend', conversationHtml);
        } else {
            conversationsList.insertAdjacentHTML('beforeend', conversationHtml);
        }

        // Attach click listener to the new conversation item
        const newItem = conversationsList.querySelector(`.conversation-item[href*="user=${conv.idutente}"]`);
        if (newItem) {
            newItem.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = this.extractUserIdFromUrl(newItem.href);
                if (userId) {
                    this.loadConversation(userId);
                }
            });
        }

        console.log('New conversation added to sidebar:', conv.nome);
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on messages page
    if (document.querySelector('.messages-container')) {
        MessagesManager.init();
    }
});

// Stop polling when user leaves page
window.addEventListener('beforeunload', () => {
    MessagesManager.stopPolling();
    MessagesManager.stopConversationListPolling();
});

// Handle browser back/forward
window.addEventListener('popstate', (e) => {
    if (e.state && e.state.userId) {
        MessagesManager.loadConversation(e.state.userId);
    }
});

// Make MessagesManager available globally
window.MessagesManager = MessagesManager;
