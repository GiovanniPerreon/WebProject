'use strict';

/**
 * Forms Module
 * Handles form validation and file upload enhancements
 *
 * Features:
 * - Real-time validation with debouncing
 * - Password strength indicator
 * - Password confirmation matching
 * - File upload preview with drag-and-drop
 * - File size/type validation
 * - Accessibility support (ARIA attributes)
 * - Progressive enhancement
 */

const FormsModule = (() => {
    // ========================================
    // PRIVATE CONSTANTS
    // ========================================

    const CONFIG = {
        FILE_MAX_SIZE: 500 * 1024, // 500KB in bytes
        ALLOWED_EXTENSIONS: ['jpg', 'jpeg', 'png', 'gif'],
        ALLOWED_MIME_TYPES: ['image/jpeg', 'image/png', 'image/gif'],
        DEBOUNCE_DELAY: 300, // milliseconds
        PASSWORD_MIN_LENGTH: 6,
        USERNAME_MIN_LENGTH: 3
    };

    const VALIDATION_MESSAGES = {
        required: 'Questo campo è obbligatorio',
        minlength: (min) => `Minimo ${min} caratteri richiesti`,
        email: 'Inserisci un indirizzo email valido',
        password_mismatch: 'Le password non corrispondono',
        file_size: (sizeKB) => `File troppo grande (${sizeKB}KB). Massimo 500KB`,
        file_type: 'Formato file non valido. Usa: jpg, jpeg, png, gif'
    };

    // ========================================
    // PRIVATE STATE
    // ========================================

    let debounceTimers = new Map();

    // ========================================
    // UTILITY FUNCTIONS
    // ========================================

    const utils = {
        /**
         * Debounce function to limit validation frequency
         */
        debounce(fn, delay, key) {
            if (debounceTimers.has(key)) {
                clearTimeout(debounceTimers.get(key));
            }

            const timer = setTimeout(fn, delay);
            debounceTimers.set(key, timer);
        },

        /**
         * Check if element is visible (for conditional validation)
         */
        isVisible(element) {
            return element.offsetWidth > 0 && element.offsetHeight > 0;
        },

        /**
         * Get file extension from filename
         */
        getFileExtension(filename) {
            return filename.slice((filename.lastIndexOf('.') - 1 >>> 0) + 2).toLowerCase();
        },

        /**
         * Format bytes to KB for display
         */
        formatBytes(bytes) {
            return Math.round(bytes / 1024);
        },

        /**
         * Show notification using global Notifications system
         */
        notify(message, type = 'info') {
            if (window.Notifications && typeof window.Notifications[type] === 'function') {
                window.Notifications[type](message);
            } else {
                // Fallback to console if Notifications not available
                console.log(`[${type.toUpperCase()}] ${message}`);
            }
        }
    };

    // ========================================
    // VALIDATION MODULE
    // ========================================

    const validator = {
        /**
         * Validate a single field
         */
        validateField(field) {
            // Skip hidden fields and invisible fields
            if (field.type === 'hidden' || !utils.isVisible(field)) {
                return { valid: true };
            }

            const value = field.value.trim();
            const rules = this.getValidationRules(field);

            // Check each rule
            for (const [rule, ruleValue] of Object.entries(rules)) {
                const result = this.checkRule(field, rule, ruleValue, value);
                if (!result.valid) {
                    return result;
                }
            }

            return { valid: true };
        },

        /**
         * Get validation rules for a field
         */
        getValidationRules(field) {
            const rules = {};

            // Required
            if (field.hasAttribute('required')) {
                rules.required = true;
            }

            // Minlength
            if (field.hasAttribute('minlength')) {
                rules.minlength = parseInt(field.getAttribute('minlength'));
            }

            // Email
            if (field.type === 'email') {
                rules.email = true;
            }

            // Password confirmation
            if (field.id === 'conferma_password' || field.name === 'conferma_password') {
                rules.password_match = true;
            }

            return rules;
        },

        /**
         * Check a specific validation rule
         */
        checkRule(field, rule, ruleValue, value) {
            switch (rule) {
                case 'required':
                    if (!value) {
                        return { valid: false, message: VALIDATION_MESSAGES.required };
                    }
                    break;

                case 'minlength':
                    if (value.length < ruleValue) {
                        return { valid: false, message: VALIDATION_MESSAGES.minlength(ruleValue) };
                    }
                    break;

                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (value && !emailRegex.test(value)) {
                        return { valid: false, message: VALIDATION_MESSAGES.email };
                    }
                    break;

                case 'password_match':
                    const passwordField = document.getElementById('password');
                    if (passwordField && value !== passwordField.value) {
                        return { valid: false, message: VALIDATION_MESSAGES.password_mismatch };
                    }
                    break;
            }

            return { valid: true };
        },

        /**
         * Validate entire form
         */
        validateForm(form) {
            const fields = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="checkbox"]), textarea, select');
            let isValid = true;
            let firstInvalidField = null;

            fields.forEach(field => {
                const result = this.validateField(field);

                if (!result.valid) {
                    isValid = false;
                    ui.showError(field, result.message);

                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    ui.clearError(field);
                }
            });

            // Focus first invalid field
            if (firstInvalidField) {
                firstInvalidField.focus();
            }

            return isValid;
        }
    };

    // ========================================
    // UI MODULE
    // ========================================

    const ui = {
        /**
         * Show validation error message
         */
        showError(field, message) {
            // Remove existing error if present
            this.clearError(field);

            // Create error element
            const errorEl = document.createElement('span');
            errorEl.className = 'field-error';
            errorEl.textContent = message;
            errorEl.setAttribute('role', 'alert');

            // Insert after field
            field.parentNode.insertBefore(errorEl, field.nextSibling);

            // Add error class to field
            field.classList.add('invalid');
            field.setAttribute('aria-invalid', 'true');
        },

        /**
         * Clear validation error
         */
        clearError(field) {
            // Remove error message
            const errorEl = field.parentNode.querySelector('.field-error');
            if (errorEl) {
                errorEl.remove();
            }

            // Remove error class
            field.classList.remove('invalid');
            field.removeAttribute('aria-invalid');
        }
    };

    // ========================================
    // PASSWORD MODULE
    // ========================================

    const password = {
        /**
         * Initialize password strength indicator
         */
        init() {
            const passwordField = document.getElementById('password');

            if (!passwordField) {
                return;
            }

            // Only show strength indicator on registration page (has confirmation field)
            const confirmField = document.getElementById('conferma_password');
            if (!confirmField) {
                return;
            }

            // Create strength indicator
            const strengthIndicator = this.createStrengthIndicator();
            passwordField.parentNode.insertBefore(strengthIndicator, passwordField.nextSibling);

            // Listen for input
            passwordField.addEventListener('input', (e) => {
                this.updateStrength(e.target.value);
            });
        },

        /**
         * Create strength indicator DOM element
         */
        createStrengthIndicator() {
            const container = document.createElement('div');
            container.className = 'password-strength';
            container.innerHTML = `
                <div class="strength-bar">
                    <div class="strength-bar-fill" id="strength-bar-fill"></div>
                </div>
                <span class="strength-text" id="strength-text"></span>
            `;
            return container;
        },

        /**
         * Update password strength display
         */
        updateStrength(password) {
            const fill = document.getElementById('strength-bar-fill');
            const text = document.getElementById('strength-text');

            if (!fill || !text) {
                return;
            }

            if (!password) {
                fill.style.width = '0%';
                fill.className = 'strength-bar-fill';
                text.textContent = '';
                return;
            }

            const strength = this.calculateStrength(password);
            const strengthInfo = this.getStrengthInfo(strength);

            // Update UI
            fill.style.width = `${(strength / 5) * 100}%`;
            fill.className = `strength-bar-fill ${strengthInfo.class}`;
            text.textContent = strengthInfo.text;
        },

        /**
         * Calculate password strength (0-5)
         */
        calculateStrength(password) {
            let strength = 0;

            // Length checks
            if (password.length >= CONFIG.PASSWORD_MIN_LENGTH) strength++;
            if (password.length >= 10) strength++;

            // Character variety
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            return strength;
        },

        /**
         * Get strength info (text and CSS class)
         */
        getStrengthInfo(strength) {
            if (strength <= 1) {
                return { text: 'Debole', class: 'weak' };
            } else if (strength <= 3) {
                return { text: 'Media', class: 'medium' };
            } else {
                return { text: 'Forte', class: 'strong' };
            }
        }
    };

    // ========================================
    // FILE UPLOAD MODULE
    // ========================================

    const fileUpload = {
        /**
         * Initialize file upload enhancements
         */
        init() {
            const fileInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');

            fileInputs.forEach(input => {
                this.enhanceFileInput(input);
            });
        },

        /**
         * Enhance a single file input
         */
        enhanceFileInput(input) {
            // Add change listener for validation and preview
            input.addEventListener('change', (e) => {
                this.handleFileChange(e.target);
            });

            // Add drag and drop support
            this.initDragDrop(input);
        },

        /**
         * Handle file selection
         */
        handleFileChange(input) {
            const file = input.files[0];

            if (!file) {
                this.updateFileName(input, 'Nessun file selezionato');
                this.removePreview(input);
                return;
            }

            // Update file name display
            this.updateFileName(input, file.name);

            // Validate file
            const validation = this.validateFile(file);

            if (!validation.valid) {
                utils.notify(validation.message, 'error');
                this.clearFileInput(input);
                return;
            }

            // Show preview
            this.showPreview(file, input);
        },

        /**
         * Update file name display
         */
        updateFileName(input, fileName) {
            const fileNameDisplay = input.parentElement.parentElement.querySelector('.file-name');
            const labelText = input.parentElement.querySelector('.file-text');

            if (fileNameDisplay) {
                fileNameDisplay.textContent = fileName;
            }

            if (labelText) {
                labelText.textContent = fileName !== 'Nessun file selezionato' ? 'Cambia immagine' : 'Scegli un\'immagine';
            }
        },

        /**
         * Validate file (size and type)
         */
        validateFile(file) {
            // Check file size
            if (file.size > CONFIG.FILE_MAX_SIZE) {
                const sizeKB = utils.formatBytes(file.size);
                return { valid: false, message: VALIDATION_MESSAGES.file_size(sizeKB) };
            }

            // Check file extension
            const extension = utils.getFileExtension(file.name);
            if (!CONFIG.ALLOWED_EXTENSIONS.includes(extension)) {
                return { valid: false, message: VALIDATION_MESSAGES.file_type };
            }

            // Check MIME type
            if (!CONFIG.ALLOWED_MIME_TYPES.includes(file.type)) {
                return { valid: false, message: VALIDATION_MESSAGES.file_type };
            }

            return { valid: true };
        },

        /**
         * Show image preview
         */
        showPreview(file, input) {
            const reader = new FileReader();

            reader.onload = (e) => {
                // Remove existing preview
                this.removePreview(input);

                // Create preview element
                const preview = document.createElement('div');
                preview.className = 'upload-preview';
                preview.innerHTML = `
                    <button type="button" class="remove-preview" aria-label="Rimuovi anteprima">×</button>
                    <img src="${e.target.result}" alt="Anteprima immagine" />
                `;

                // Insert preview after file input wrapper
                input.parentElement.parentElement.insertBefore(preview, input.parentElement.nextSibling);

                // Add remove listener
                preview.querySelector('.remove-preview').addEventListener('click', () => {
                    this.clearFileInput(input);
                    this.removePreview(input);
                });
            };

            reader.readAsDataURL(file);
        },

        /**
         * Remove preview
         */
        removePreview(input) {
            const preview = input.parentElement.parentElement.querySelector('.upload-preview');
            if (preview) {
                preview.remove();
            }
        },

        /**
         * Clear file input
         */
        clearFileInput(input) {
            input.value = '';
            this.updateFileName(input, 'Nessun file selezionato');
        },

        /**
         * Initialize drag and drop
         */
        initDragDrop(input) {
            const wrapper = input.parentElement;

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                wrapper.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // Highlight drop zone
            ['dragenter', 'dragover'].forEach(eventName => {
                wrapper.addEventListener(eventName, () => {
                    wrapper.classList.add('drag-over');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                wrapper.addEventListener(eventName, () => {
                    wrapper.classList.remove('drag-over');
                }, false);
            });

            // Handle drop
            wrapper.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;

                if (files.length > 0) {
                    const file = files[0];

                    // Check if it's an image
                    if (!file.type.startsWith('image/')) {
                        utils.notify('Per favore, carica solo immagini.', 'error');
                        return;
                    }

                    // Set file to input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;

                    // Trigger change event
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }, false);
        }
    };

    // ========================================
    // FORM VALIDATION MODULE
    // ========================================

    const formValidation = {
        /**
         * Initialize form validation
         */
        init() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                // Skip specific forms that have their own handlers
                if (form.id === 'message-form' || form.id === 'comment-form') {
                    return;
                }

                this.attachFormListeners(form);
            });
        },

        /**
         * Attach listeners to form
         */
        attachFormListeners(form) {
            // Get all validatable fields
            const fields = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="checkbox"]):not([type="file"]), textarea');

            // Add real-time validation
            fields.forEach(field => {
                // Validate on blur
                field.addEventListener('blur', () => {
                    const result = validator.validateField(field);

                    if (!result.valid) {
                        ui.showError(field, result.message);
                    } else {
                        ui.clearError(field);
                    }
                });

                // Clear error on input (debounced)
                field.addEventListener('input', () => {
                    utils.debounce(() => {
                        const result = validator.validateField(field);

                        if (result.valid) {
                            ui.clearError(field);
                        }
                    }, CONFIG.DEBOUNCE_DELAY, field.id || field.name);
                });
            });

            // Validate on submit
            form.addEventListener('submit', (e) => {
                const isValid = validator.validateForm(form);

                if (!isValid) {
                    e.preventDefault();
                    utils.notify('Correggi gli errori nel modulo prima di continuare', 'error');
                }
            });

            // Special handling for password confirmation
            const passwordField = document.getElementById('password');
            const confirmField = document.getElementById('conferma_password');

            if (passwordField && confirmField) {
                passwordField.addEventListener('input', () => {
                    // Re-validate confirmation field if it has a value
                    if (confirmField.value) {
                        const result = validator.validateField(confirmField);

                        if (!result.valid) {
                            ui.showError(confirmField, result.message);
                        } else {
                            ui.clearError(confirmField);
                        }
                    }
                });
            }
        }
    };

    // ========================================
    // PUBLIC API
    // ========================================

    return {
        /**
         * Initialize the forms module
         */
        init() {
            console.log('Forms module initialized');

            // Initialize all sub-modules
            formValidation.init();
            password.init();
            fileUpload.init();
        },

        // Expose for testing
        validator,
        utils
    };
})();

// ========================================
// AUTO-INITIALIZE
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    FormsModule.init();
});

// Make module available globally for testing
window.FormsModule = FormsModule;
