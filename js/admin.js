'use strict';

/**
 * Admin Panel Module
 * Handles admin operations with modern UX (toast notifications instead of alerts)
 */

const AdminPanel = {
    /**
     * Initialize admin panel
     */
    init() {
        console.log('Admin Panel initialized');

        this.initDeletePostButtons();
        this.initDeleteCommentButtons();
        this.initAddTagForm();
        this.initSaveTagButtons();
        this.initDeleteTagButtons();
        this.initReportStatusSelects();
    },

    /**
     * Initialize delete post buttons
     */
    initDeletePostButtons() {
        document.querySelectorAll('.btn-delete-post').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idpost = e.currentTarget.getAttribute('data-id');

                if (confirm('Sei sicuro di voler eliminare questo post? Questa azione è irreversibile.')) {
                    this.deletePost(idpost, e.currentTarget);
                }
            });
        });
    },

    /**
     * Delete a post
     */
    async deletePost(idpost, button) {
        // Disable button during request
        button.disabled = true;
        button.textContent = 'Eliminazione...';

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_post&idpost=${idpost}`
            });

            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Post eliminato con successo');

                // Reload page after short delay to show notification
                setTimeout(() => location.reload(), 1000);
            } else {
                Notifications.error(data.message || 'Errore durante l\'eliminazione del post');
                button.disabled = false;
                button.textContent = 'Elimina';
            }
        } catch (err) {
            console.error('Delete post error:', err);
            Notifications.error('Errore di connessione');
            button.disabled = false;
            button.textContent = 'Elimina';
        }
    },

    /**
     * Initialize delete comment buttons
     */
    initDeleteCommentButtons() {
        document.querySelectorAll('.btn-delete-comment').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idcommento = e.currentTarget.getAttribute('data-id');

                if (confirm('Sei sicuro di voler eliminare questo commento?')) {
                    this.deleteComment(idcommento, e.currentTarget);
                }
            });
        });
    },

    /**
     * Delete a comment
     */
    async deleteComment(idcommento, button) {
        // Disable button during request
        button.disabled = true;
        button.textContent = 'Eliminazione...';

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_comment&idcommento=${idcommento}`
            });

            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Commento eliminato con successo');

                // Reload page after short delay to show notification
                setTimeout(() => location.reload(), 1000);
            } else {
                Notifications.error(data.message || 'Errore durante l\'eliminazione del commento');
                button.disabled = false;
                button.textContent = 'Elimina';
            }
        } catch (err) {
            console.error('Delete comment error:', err);
            Notifications.error('Errore di connessione');
            button.disabled = false;
            button.textContent = 'Elimina';
        }
    },

    /**
     * Initialize add tag form
     */
    initAddTagForm() {
        const addTagForm = document.getElementById('addTagForm');

        if (!addTagForm) {
            return;
        }

        addTagForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const nometag = document.getElementById('newTagName').value.trim();

            if (!nometag) {
                Notifications.error('Il nome del tag non può essere vuoto');
                return;
            }

            this.addTag(nometag, addTagForm);
        });
    },

    /**
     * Add a new tag
     */
    async addTag(nometag, form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const input = form.querySelector('#newTagName');

        // Disable form during request
        submitBtn.disabled = true;
        submitBtn.textContent = 'Aggiunta...';

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add_tag&nometag=${encodeURIComponent(nometag)}`
            });

            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Tag aggiunto con successo');

                // Clear input and reload after short delay
                input.value = '';
                setTimeout(() => location.reload(), 1000);
            } else {
                Notifications.error(data.message || 'Errore durante l\'aggiunta del tag');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Aggiungi Tag';
            }
        } catch (err) {
            console.error('Add tag error:', err);
            Notifications.error('Errore di connessione');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Aggiungi Tag';
        }
    },

    /**
     * Initialize save tag buttons
     */
    initSaveTagButtons() {
        document.querySelectorAll('.btn-save-tag').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tagItem = e.currentTarget.closest('.tag-item');
                const idtag = tagItem.getAttribute('data-id');
                const nometag = tagItem.querySelector('.tag-name-input').value.trim();

                if (!nometag) {
                    Notifications.error('Il nome del tag non può essere vuoto');
                    return;
                }

                this.updateTag(idtag, nometag, e.currentTarget);
            });
        });
    },

    /**
     * Update a tag
     */
    async updateTag(idtag, nometag, button) {
        // Disable button during request
        button.disabled = true;
        const originalText = button.textContent;
        button.textContent = 'Salvataggio...';

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_tag&idtag=${idtag}&nometag=${encodeURIComponent(nometag)}`
            });

            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Tag aggiornato con successo');
            } else {
                Notifications.error(data.message || 'Errore durante l\'aggiornamento del tag');
            }

            // Re-enable button
            button.disabled = false;
            button.textContent = originalText;
        } catch (err) {
            console.error('Update tag error:', err);
            Notifications.error('Errore di connessione');
            button.disabled = false;
            button.textContent = originalText;
        }
    },

    /**
     * Initialize delete tag buttons
     */
    initDeleteTagButtons() {
        document.querySelectorAll('.btn-delete-tag').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tagItem = e.currentTarget.closest('.tag-item');
                const idtag = tagItem.getAttribute('data-id');

                if (confirm('Sei sicuro di voler eliminare questo tag? Verrà rimosso da tutti i post.')) {
                    this.deleteTag(idtag, tagItem, e.currentTarget);
                }
            });
        });
    },

    /**
     * Delete a tag
     */
    async deleteTag(idtag, tagItem, button) {
        // Disable button during request
        button.disabled = true;
        button.textContent = 'Eliminazione...';

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete_tag&idtag=${idtag}`
            });

            const data = await response.json();

            if (data.success) {
                Notifications.success(data.message || 'Tag eliminato con successo');

                // Remove tag item from DOM with animation
                tagItem.style.opacity = '0';
                tagItem.style.transform = 'translateX(-20px)';
                tagItem.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    tagItem.remove();
                }, 300);
            } else {
                Notifications.error(data.message || 'Errore durante l\'eliminazione del tag');
                button.disabled = false;
                button.textContent = 'Elimina';
            }
        } catch (err) {
            console.error('Delete tag error:', err);
            Notifications.error('Errore di connessione');
            button.disabled = false;
            button.textContent = 'Elimina';
        }
    },

    /**
     * Initialize report status select dropdowns
     */
    initReportStatusSelects() {
        document.querySelectorAll('.stato-select').forEach(select => {
            select.addEventListener('change', (e) => {
                const idsegnalazione = e.currentTarget.getAttribute('data-id');
                const stato = e.currentTarget.value;

                this.updateReportStatus(idsegnalazione, stato, e.currentTarget);
            });
        });
    },

    /**
     * Update report status
     */
    async updateReportStatus(idsegnalazione, stato, selectElement) {
        // Store original value in case we need to revert
        const originalValue = selectElement.dataset.originalValue || selectElement.value;
        selectElement.dataset.originalValue = originalValue;

        // Disable select during request
        selectElement.disabled = true;

        try {
            const response = await fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_segnalazione&idsegnalazione=${idsegnalazione}&stato=${stato}`
            });

            const data = await response.json();

            if (data.success) {
                // Update visual state
                const item = selectElement.closest('.segnalazione-item');
                item.className = 'segnalazione-item stato-' + stato;

                const statoLabel = item.querySelector('.segnalazione-stato');
                if (statoLabel) {
                    statoLabel.className = 'segnalazione-stato stato-' + stato;
                    statoLabel.textContent = this.getStatoLabel(stato);
                }

                // Update stored value
                selectElement.dataset.originalValue = stato;

                Notifications.success('Stato segnalazione aggiornato');
            } else {
                // Revert to original value on error
                selectElement.value = originalValue;
                Notifications.error(data.message || 'Errore durante l\'aggiornamento dello stato');
            }

            // Re-enable select
            selectElement.disabled = false;
        } catch (err) {
            console.error('Update report status error:', err);

            // Revert to original value on error
            selectElement.value = originalValue;
            selectElement.disabled = false;

            Notifications.error('Errore di connessione');
        }
    },

    /**
     * Get Italian label for report status
     */
    getStatoLabel(stato) {
        const labels = {
            'pending': 'In attesa',
            'reviewed': 'In revisione',
            'resolved': 'Risolto',
            'dismissed': 'Respinto'
        };

        return labels[stato] || stato;
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if we're on admin page
    if (document.querySelector('.admin-section') || document.getElementById('addTagForm')) {
        AdminPanel.init();
    }
});

// Make AdminPanel available globally for debugging
window.AdminPanel = AdminPanel;
