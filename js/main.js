'use strict';

/**
 * Main JavaScript Module - Core Application Framework
 * Spotted Unibo Cesena
 *
 * Purpose: Provides core functionality, utilities, and global event handling
 * Loading: Loaded globally on all pages via template/base.php
 *
 * Responsibilities:
 * - Application initialization and configuration
 * - Reusable utility functions for other modules
 * - Global keyboard shortcuts and event handlers
 * - Modal management system
 * - Accessibility features
 * - Cross-browser compatibility helpers
 */

const SpottedApp = (function() {
    'use strict';

    // ============================================
    // PRIVATE VARIABLES
    // ============================================

    const config = {
        ajaxTimeout: 5000,
        maxImageSize: 500000, // 500KB in bytes
        toastDuration: 3000,
        debounceDelay: 300,
        animationDuration: 200
    };

    let modals = [];
    let currentOpenModal = null;

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================

    const utils = {
        /**
         * Debounce function - delays execution until after wait time
         * Useful for search inputs, scroll events, resize handlers
         */
        debounce: function(func, wait = config.debounceDelay) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Throttle function - limits execution to once per wait period
         * Useful for scroll events, mousemove, resize
         */
        throttle: function(func, wait = config.debounceDelay) {
            let inThrottle;
            return function(...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, wait);
                }
            };
        },

        /**
         * Format date string to Italian locale
         */
        formatDate: function(dateString, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const date = new Date(dateString);
            return date.toLocaleDateString('it-IT', { ...defaultOptions, ...options });
        },

        /**
         * Format date with time
         */
        formatDateTime: function(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('it-IT', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        /**
         * Sanitize HTML to prevent XSS (basic client-side protection)
         */
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        /**
         * Copy text to clipboard
         */
        copyToClipboard: function(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text);
            } else {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    return Promise.resolve();
                } catch (err) {
                    document.body.removeChild(textarea);
                    return Promise.reject(err);
                }
            }
        },

        /**
         * Validate image file before upload
         */
        validateImageFile: function(file) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!file) {
                return { valid: false, error: 'Nessun file selezionato' };
            }

            // Check file size
            if (file.size > config.maxImageSize) {
                const maxKB = config.maxImageSize / 1024;
                const currentKB = (file.size / 1024).toFixed(2);
                return {
                    valid: false,
                    error: `Immagine troppo grande. Max: ${maxKB}KB, Attuale: ${currentKB}KB`
                };
            }

            // Check MIME type
            if (!allowedTypes.includes(file.type)) {
                return {
                    valid: false,
                    error: `Tipo file non valido. Accettati: ${allowedExtensions.join(', ')}`
                };
            }

            // Check file extension
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                return {
                    valid: false,
                    error: `Estensione non valida. Accettate: ${allowedExtensions.join(', ')}`
                };
            }

            return { valid: true };
        },

        /**
         * Smooth scroll to element
         */
        scrollToElement: function(element, offset = 0) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (element) {
                const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({
                    top: elementPosition - offset,
                    behavior: 'smooth'
                });
            }
        }
    };

    // ============================================
    // MODAL MANAGEMENT SYSTEM
    // ============================================

    const ModalManager = {
        /**
         * Initialize all modals on the page
         */
        init: function() {
            modals = Array.from(document.querySelectorAll('.modal'));

            // Setup close buttons for all modals
            modals.forEach(modal => {
                const closeBtn = modal.querySelector('.modal-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => this.close(modal));
                }
            });

            // Close modal on outside click
            window.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal')) {
                    this.close(e.target);
                }
            });

            // Initialize report modal specifically
            this.initReportModal();
        },

        /**
         * Open a modal
         */
        open: function(modal) {
            if (typeof modal === 'string') {
                modal = document.getElementById(modal);
            }
            if (modal) {
                modal.style.display = 'flex';
                currentOpenModal = modal;
                document.body.style.overflow = 'hidden'; // Prevent background scrolling

                // Focus first input if exists
                const firstInput = modal.querySelector('input:not([type="hidden"]), textarea, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        },

        /**
         * Close a modal
         */
        close: function(modal) {
            if (typeof modal === 'string') {
                modal = document.getElementById(modal);
            }
            if (modal) {
                modal.style.display = 'none';
                currentOpenModal = null;
                document.body.style.overflow = ''; // Re-enable scrolling

                // Reset form if modal contains one
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                }
            }
        },

        /**
         * Close currently open modal
         */
        closeCurrent: function() {
            if (currentOpenModal) {
                this.close(currentOpenModal);
            }
        },

        /**
         * Initialize report modal functionality
         */
        initReportModal: function() {
            const modal = document.getElementById('segnalaModal');
            if (!modal) return;

            // Open modal on report button click
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('segnala-btn') || e.target.closest('.segnala-btn')) {
                    const btn = e.target.classList.contains('segnala-btn') ? e.target : e.target.closest('.segnala-btn');
                    const idpost = btn.getAttribute('data-idpost') || '';
                    const idcommento = btn.getAttribute('data-idcommento') || '';

                    document.getElementById('segnala_idpost').value = idpost;
                    document.getElementById('segnala_idcommento').value = idcommento;

                    this.open(modal);
                    e.preventDefault();
                }
            });

            // Submit report form
            const segnalaForm = document.getElementById('segnalaForm');
            if (segnalaForm) {
                segnalaForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const formData = new FormData(segnalaForm);

                    fetch('processa-segnalazione.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (typeof showNotification === 'function') {
                            showNotification(data.message, data.success ? 'success' : 'error');
                        } else {
                            alert(data.message);
                        }

                        if (data.success) {
                            this.close(modal);
                        }
                    })
                    .catch(err => {
                        console.error('Error submitting report:', err);
                        if (typeof showNotification === 'function') {
                            showNotification('Errore nell\'invio della segnalazione', 'error');
                        } else {
                            alert('Errore nell\'invio della segnalazione');
                        }
                    });
                });
            }
        }
    };

    // ============================================
    // GLOBAL KEYBOARD SHORTCUTS
    // ============================================

    const KeyboardHandler = {
        init: function() {
            document.addEventListener('keydown', (e) => {
                // ESC key - Close current modal
                if (e.key === 'Escape' || e.keyCode === 27) {
                    ModalManager.closeCurrent();
                }

                // Ctrl/Cmd + K - Focus search (if exists)
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    const searchInput = document.querySelector('input[type="search"], input[name="search"]');
                    if (searchInput) {
                        e.preventDefault();
                        searchInput.focus();
                    }
                }
            });
        }
    };

    // ============================================
    // ACCESSIBILITY FEATURES
    // ============================================

    const Accessibility = {
        init: function() {
            this.setupSkipLinks();
            this.setupFocusManagement();
            this.improveFormAccessibility();
        },

        /**
         * Setup skip to main content link
         */
        setupSkipLinks: function() {
            const main = document.querySelector('main');
            if (main && !main.id) {
                main.id = 'main-content';
            }
        },

        /**
         * Manage focus for better keyboard navigation
         */
        setupFocusManagement: function() {
            // Add focus visible class for better keyboard navigation styling
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-nav');
                }
            });

            document.addEventListener('mousedown', () => {
                document.body.classList.remove('keyboard-nav');
            });
        },

        /**
         * Improve form accessibility
         */
        improveFormAccessibility: function() {
            // Add aria-required to required fields if not present
            document.querySelectorAll('input[required], textarea[required], select[required]').forEach(field => {
                if (!field.hasAttribute('aria-required')) {
                    field.setAttribute('aria-required', 'true');
                }
            });

            // Add aria-invalid to fields with errors
            document.querySelectorAll('.form-error, .error').forEach(errorMsg => {
                const field = errorMsg.previousElementSibling;
                if (field && (field.tagName === 'INPUT' || field.tagName === 'TEXTAREA' || field.tagName === 'SELECT')) {
                    field.setAttribute('aria-invalid', 'true');
                    field.setAttribute('aria-describedby', errorMsg.id || 'error-' + field.name);
                }
            });
        }
    };

    // ============================================
    // RESPONSIVE HELPERS
    // ============================================

    const ResponsiveHelpers = {
        init: function() {
            this.setupViewportHeight();
            this.watchViewportChanges();
        },

        /**
         * Fix viewport height on mobile (accounts for address bar)
         */
        setupViewportHeight: function() {
            const setVH = () => {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            };

            setVH();
            window.addEventListener('resize', utils.throttle(setVH, 100));
        },

        /**
         * Watch for viewport changes
         */
        watchViewportChanges: function() {
            // Add viewport size class to body for CSS targeting
            const updateViewportClass = () => {
                const width = window.innerWidth;
                document.body.classList.remove('viewport-mobile', 'viewport-tablet', 'viewport-desktop');

                if (width < 768) {
                    document.body.classList.add('viewport-mobile');
                } else if (width < 1024) {
                    document.body.classList.add('viewport-tablet');
                } else {
                    document.body.classList.add('viewport-desktop');
                }
            };

            updateViewportClass();
            window.addEventListener('resize', utils.throttle(updateViewportClass, 200));
        }
    };

    // ============================================
    // PERFORMANCE MONITORING (Development)
    // ============================================

    const Performance = {
        logPageLoad: function() {
            if (window.performance && window.performance.timing) {
                window.addEventListener('load', () => {
                    const perfData = window.performance.timing;
                    const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                    const connectTime = perfData.responseEnd - perfData.requestStart;

                    console.log('🚀 Page Performance:');
                    console.log(`   Total Load Time: ${pageLoadTime}ms`);
                    console.log(`   Server Response: ${connectTime}ms`);
                    console.log(`   DOM Ready: ${perfData.domContentLoadedEventEnd - perfData.navigationStart}ms`);
                });
            }
        }
    };

    // ============================================
    // PUBLIC API
    // ============================================

    return {
        // Configuration
        config: config,

        // Utilities
        utils: utils,

        // Modal management
        modal: {
            open: ModalManager.open.bind(ModalManager),
            close: ModalManager.close.bind(ModalManager),
            closeCurrent: ModalManager.closeCurrent.bind(ModalManager)
        },

        // Initialization
        init: function() {
            console.log('🌲 Spotted Unibo Cesena - Initializing Core Module');

            // Initialize all subsystems
            ModalManager.init();
            KeyboardHandler.init();
            Accessibility.init();
            ResponsiveHelpers.init();

            // Log performance in development
            if (window.location.hostname === 'localhost') {
                Performance.logPageLoad();
            }

            console.log('✅ Core module initialized successfully');
        }
    };
})();

// ============================================
// AUTO-INITIALIZE ON DOM READY
// ============================================

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', SpottedApp.init.bind(SpottedApp));
} else {
    // DOM already loaded (e.g., script loaded dynamically)
    SpottedApp.init();
}

// Make SpottedApp globally accessible for other modules
window.SpottedApp = SpottedApp;
