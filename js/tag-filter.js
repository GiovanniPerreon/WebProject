'use strict';

/**
 * Tag Filter Module
 * Handles AJAX-based tag filtering without page reloads
 *
 * DEPENDENCIES:
 * - main.js (SpottedApp) for utilities
 * - notifications.js for toast messages
 *
 * INTEGRATIONS:
 * - XSS prevention (uses SpottedApp.utils.escapeHtml)
 * - Smooth scrolling (uses SpottedApp.utils.scrollToElement)
 * - Consistent timing (uses SpottedApp.config.animationDuration)
 * - History API for browser back/forward support
 * - Progressive enhancement (works without JS)
 */

const TagFilter = {
    currentTagId: null,
    isHomepage: false,

    /**
     * Initialize tag filter system
     */
    init() {
        console.log('Tag Filter initialized');

        // Check if SpottedApp is available
        if (!window.SpottedApp) {
            console.warn('TagFilter: SpottedApp not loaded. Using fallback utilities.');
        }

        // Detect if we're on homepage or tag page
        this.detectCurrentPage();

        // Attach click listeners to tag links
        this.attachTagListeners();

        // Handle browser back/forward buttons
        this.attachHistoryListener();

        // Highlight active tag in sidebar
        this.updateActiveTag(this.currentTagId);
    },

    /**
     * Detect current page and tag
     */
    detectCurrentPage() {
        const urlParams = new URLSearchParams(window.location.search);
        const tagId = urlParams.get('id');

        if (window.location.pathname.includes('tag.php') && tagId) {
            this.currentTagId = parseInt(tagId);
            this.isHomepage = false;
        } else if (window.location.pathname.includes('index.php') || window.location.pathname.endsWith('/')) {
            this.currentTagId = null;
            this.isHomepage = true;
        }
    },

    /**
     * Attach click listeners to tag links
     */
    attachTagListeners() {
        // Sidebar tag links
        document.querySelectorAll('aside a[href*="tag.php"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = new URL(link.href);
                const tagId = url.searchParams.get('id');
                const tagName = link.textContent.trim();
                this.filterByTag(parseInt(tagId), tagName);
            });
        });

        // Home link (show all posts)
        const homeLink = document.querySelector('nav a[href="index.php"]');
        if (homeLink) {
            homeLink.addEventListener('click', (e) => {
                // Only prevent default if we're already on homepage or tag page
                if (this.isHomepage || this.currentTagId !== null) {
                    e.preventDefault();
                    this.showAllPosts();
                }
            });
        }
    },

    /**
     * Filter posts by tag using AJAX
     */
    async filterByTag(tagId, tagName) {
        if (this.currentTagId === tagId) {
            // Already showing this tag
            return;
        }

        // Show loading state
        this.showLoadingState();

        try {
            const response = await fetch(`api-posts.php?action=getByTag&idtag=${tagId}`);
            const data = await response.json();

            if (data.success) {
                // Render posts
                this.renderPosts(data.posts);

                // Update state
                this.currentTagId = tagId;
                this.isHomepage = false;

                // Update URL and title
                this.updateURL(`tag.php?id=${tagId}`, `Spotted Unibo Cesena - ${tagName}`, { tagId, tagName });

                // Update active tag in sidebar
                this.updateActiveTag(tagId);

                // Show success notification
                Notifications.success(`Filtro applicato: ${tagName} (${data.count} post)`);
            } else {
                Notifications.error(data.error || 'Errore nel caricamento dei post');
                this.hideLoadingState();
            }
        } catch (err) {
            console.error('Tag filter error:', err);
            Notifications.error('Errore di connessione');
            this.hideLoadingState();
        }
    },

    /**
     * Show all posts (homepage view)
     */
    async showAllPosts() {
        if (this.isHomepage && this.currentTagId === null) {
            // Already showing all posts
            return;
        }

        // Show loading state
        this.showLoadingState();

        try {
            const response = await fetch('api-posts.php?action=getAll&limit=10');
            const data = await response.json();

            if (data.success) {
                // Render posts
                this.renderPosts(data.posts);

                // Update state
                this.currentTagId = null;
                this.isHomepage = true;

                // Update URL and title
                this.updateURL('index.php', 'Spotted Unibo Cesena - Home', { homepage: true });

                // Clear active tag
                this.updateActiveTag(null);

                // Show notification
                Notifications.info(`Visualizzazione tutti i post (${data.count})`);
            } else {
                Notifications.error(data.error || 'Errore nel caricamento dei post');
                this.hideLoadingState();
            }
        } catch (err) {
            console.error('Show all posts error:', err);
            Notifications.error('Errore di connessione');
            this.hideLoadingState();
        }
    },

    /**
     * Render posts in the main content area
     */
    renderPosts(posts) {
        const mainContent = document.querySelector('main');
        if (!mainContent) return;

        // Use SpottedApp escapeHtml if available, otherwise use fallback
        const escapeHtml = (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.escapeHtml)
            ? window.SpottedApp.utils.escapeHtml
            : this.escapeHtmlFallback;

        let html = '<h2>Ultimi Post</h2>';

        if (posts.length === 0) {
            html += '<p class="no-posts">Nessun post trovato.</p>';
        } else {
            posts.forEach(post => {
                html += this.renderPost(post, escapeHtml);
            });
        }

        mainContent.innerHTML = html;

        // Get animation duration from SpottedApp config
        const animDuration = (window.SpottedApp && window.SpottedApp.config)
            ? window.SpottedApp.config.animationDuration
            : 200;

        // Hide loading state after animation completes
        setTimeout(() => {
            this.hideLoadingState();
        }, animDuration);

        // Scroll to top smoothly
        if (window.SpottedApp && window.SpottedApp.utils && window.SpottedApp.utils.scrollToElement) {
            window.SpottedApp.utils.scrollToElement(mainContent, 20);
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    /**
     * Render a single post
     */
    renderPost(post, escapeHtml) {
        const isAdmin = window.userIsAdmin || false;

        // Handle anonymous posts
        let displayName = post.nome;
        let linkToProfile = true;

        if (post.anonimo == 1) {
            if (isAdmin) {
                displayName = post.nome;
                linkToProfile = true;
            } else {
                displayName = 'Anonimo';
                linkToProfile = false;
            }
        }

        const adminBadge = post.amministratore == 1 ? ' <span class="admin-badge" title="Amministratore">👑</span>' : '';
        const pinnedBadge = post.pinned == 1 ? '<span class="pinned-badge" title="Post pinnato">📌</span>' : '';
        const anonimoBadge = (post.anonimo == 1 && isAdmin) ? '<span class="admin-anonimo-badge" title="Post anonimo - Solo admin può vedere l\'autore">🎭</span>' : '';

        // Author link or plain text
        let authorHtml = '';
        if (linkToProfile && post.idutente) {
            authorHtml = `<a href="profilo.php?id=${post.idutente}" class="author-link">${escapeHtml(displayName)}</a>${adminBadge}`;
        } else {
            authorHtml = `${escapeHtml(displayName)}${adminBadge}`;
        }

        // Image
        let imageHtml = '';
        if (post.imgpost && post.imgpost.trim() !== '') {
            imageHtml = `
                <a href="post.php?id=${post.idpost}">
                    <img src="${UPLOAD_DIR}${escapeHtml(post.imgpost)}" alt="${escapeHtml(post.titolopost)}" />
                </a>
            `;
        }

        // Tags
        let tagsHtml = '';
        if (post.tags && post.tags.length > 0) {
            tagsHtml = '<p class="post-tags">';
            post.tags.forEach((tag, i) => {
                if (i > 0) tagsHtml += ' ';
                tagsHtml += `<a href="tag.php?id=${tag.idtag}" class="tag-link" data-tag-id="${tag.idtag}" data-tag-name="${escapeHtml(tag.nometag)}">${escapeHtml(tag.nometag)}</a>`;
            });
            tagsHtml += '</p>';
        }

        return `
            <article class="post-preview">
                <h3>
                    <a href="post.php?id=${post.idpost}" class="post-title-link">
                        ${escapeHtml(post.titolopost)}
                    </a>
                    ${pinnedBadge}
                    ${anonimoBadge}
                </h3>
                ${imageHtml}
                <p class="post-meta">
                    ${escapeHtml(post.datapost)} - ${authorHtml}
                </p>
                ${tagsHtml}
            </article>
        `;
    },

    /**
     * Show loading state
     */
    showLoadingState() {
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.classList.add('loading');
            mainContent.setAttribute('aria-busy', 'true');
        }
    },

    /**
     * Hide loading state
     */
    hideLoadingState() {
        const mainContent = document.querySelector('main');
        if (mainContent) {
            mainContent.classList.remove('loading');
            mainContent.removeAttribute('aria-busy');
        }
    },

    /**
     * Update browser URL without reload
     */
    updateURL(url, title, state) {
        window.history.pushState(state, title, url);
        document.title = title;
    },

    /**
     * Update active tag highlighting in sidebar
     */
    updateActiveTag(tagId) {
        // Remove active class from all tags
        document.querySelectorAll('aside a[href*="tag.php"]').forEach(link => {
            link.classList.remove('active');
        });

        if (tagId !== null) {
            // Add active class to selected tag
            const activeLink = document.querySelector(`aside a[href*="tag.php?id=${tagId}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }
    },

    /**
     * Handle browser back/forward buttons
     */
    attachHistoryListener() {
        window.addEventListener('popstate', (e) => {
            if (e.state) {
                if (e.state.homepage) {
                    this.showAllPosts();
                } else if (e.state.tagId) {
                    this.filterByTag(e.state.tagId, e.state.tagName);
                }
            }
        });
    },

    /**
     * Fallback escapeHtml if SpottedApp not available
     * DEPRECATED: Prefer SpottedApp.utils.escapeHtml
     */
    escapeHtmlFallback(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on homepage and tag pages
    if (window.location.pathname.includes('index.php') ||
        window.location.pathname.includes('tag.php') ||
        window.location.pathname.endsWith('/')) {

        // Set UPLOAD_DIR as global constant for JS
        window.UPLOAD_DIR = './upload/';

        TagFilter.init();

        // Re-attach listeners to dynamically loaded tag links
        document.addEventListener('click', (e) => {
            if (e.target.matches('.tag-link')) {
                e.preventDefault();
                const tagId = parseInt(e.target.dataset.tagId);
                const tagName = e.target.dataset.tagName || e.target.textContent.trim();
                TagFilter.filterByTag(tagId, tagName);
            }
        });
    }
});

// Make TagFilter available globally
window.TagFilter = TagFilter;
