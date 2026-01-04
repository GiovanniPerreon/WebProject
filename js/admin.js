document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.btn-delete-post').forEach(btn => {
        btn.addEventListener('click', function() {
            const idpost = this.getAttribute('data-id');
            if(confirm('Sei sicuro di voler eliminare questo post? Questa azione è irreversibile.')){
                fetch('api-admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_post&idpost=${idpost}`
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) location.reload();
                })
                .catch(err => alert('Errore nella richiesta'));
            }
        });
    });

    // Pin/unpin removed from admin panel — handled on post page only

    document.querySelectorAll('.btn-delete-comment').forEach(btn => {
        btn.addEventListener('click', function() {
            const idcommento = this.getAttribute('data-id');
            if(confirm('Sei sicuro di voler eliminare questo commento?')){
                fetch('api-admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_comment&idcommento=${idcommento}`
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) location.reload();
                })
                .catch(err => alert('Errore nella richiesta'));
            }
        });
    });

    const addTagForm = document.getElementById('addTagForm');
    if(addTagForm){
        addTagForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nometag = document.getElementById('newTagName').value.trim();
            if(!nometag) return;
            
            fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add_tag&nometag=${encodeURIComponent(nometag)}`
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if(data.success) location.reload();
            })
            .catch(err => alert('Errore nella richiesta'));
        });
    }

    document.querySelectorAll('.btn-save-tag').forEach(btn => {
        btn.addEventListener('click', function() {
            const tagItem = this.closest('.tag-item');
            const idtag = tagItem.getAttribute('data-id');
            const nometag = tagItem.querySelector('.tag-name-input').value.trim();
            
            if(!nometag){
                alert('Il nome del tag non può essere vuoto');
                return;
            }
            
            fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_tag&idtag=${idtag}&nometag=${encodeURIComponent(nometag)}`
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            })
            .catch(err => alert('Errore nella richiesta'));
        });
    });

    document.querySelectorAll('.btn-delete-tag').forEach(btn => {
        btn.addEventListener('click', function() {
            const tagItem = this.closest('.tag-item');
            const idtag = tagItem.getAttribute('data-id');
            
            if(confirm('Sei sicuro di voler eliminare questo tag? Verrà rimosso da tutti i post.')){
                fetch('api-admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_tag&idtag=${idtag}`
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) tagItem.remove();
                })
                .catch(err => alert('Errore nella richiesta'));
            }
        });
    });

    document.querySelectorAll('.stato-select').forEach(select => {
        select.addEventListener('change', function() {
            const idsegnalazione = this.getAttribute('data-id');
            const stato = this.value;
            
            fetch('api-admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update_segnalazione&idsegnalazione=${idsegnalazione}&stato=${stato}`
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // Update visual state
                    const item = this.closest('.segnalazione-item');
                    item.className = 'segnalazione-item stato-' + stato;
                    const statoLabel = item.querySelector('.segnalazione-stato');
                    statoLabel.className = 'segnalazione-stato stato-' + stato;
                    statoLabel.textContent = getStatoLabel(stato);
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Errore nella richiesta'));
        });
    });
    
    function getStatoLabel(stato) {
        switch(stato) {
            case 'pending': return 'In attesa';
            case 'reviewed': return 'In revisione';
            case 'resolved': return 'Risolto';
            case 'dismissed': return 'Respinto';
            default: return stato;
        }
    }
});
