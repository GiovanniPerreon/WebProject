<?php if(count($templateParams["post"]) == 0): ?>
<h2>Post non trovato</h2>
<section>
    <p>Il post richiesto non esiste!</p>
</section>
<?php else: 
$post = $templateParams["post"][0];

$showName = true;
$displayName = $post["nome"];
$linkToProfile = true;

if(isset($post["anonimo"]) && $post["anonimo"]) {
    if(isUserAdmin()) {
        $displayName = $post["nome"];
        $linkToProfile = true;
    } else {
        $displayName = "Anonimo";
        $linkToProfile = false;
    }
}

$adminBadge = (isset($post["amministratore"]) && $post["amministratore"]) ? ' <span class="admin-badge" title="Amministratore">👑</span>' : '';
?>
<h2>
    <?php echo $post["titolopost"]; ?>
    <?php if(isset($post["anonimo"]) && $post["anonimo"] && isUserAdmin()): ?>
    <span class="admin-anonimo-badge" title="Post anonimo">🎭</span>
    <?php endif; ?>
</h2>
<article>
    <?php if(!empty($post["imgpost"])): ?>
    <img src="<?php echo UPLOAD_DIR.$post["imgpost"]; ?>" alt="<?php echo $post["titolopost"]; ?>" />
    <?php endif; ?>
    <p><?php echo $post["testopost"]; ?></p>
    <p>
        <?php echo $post["datapost"]; ?> - 
        <?php if($linkToProfile && isset($post["idutente"])): ?>
        <a href="profilo.php?id=<?php echo $post["idutente"]; ?>" class="author-link"><?php echo $displayName; ?></a><?php echo $adminBadge; ?>
        <?php else: ?>
        <?php echo $displayName; ?><?php echo $adminBadge; ?>
        <?php endif; ?>
    </p>
    
    <div class="post-actions">
        <?php if(isUserLoggedIn()): ?>
        <button class="segnala-btn" data-idpost="<?php echo $post["idpost"]; ?>">⚠️ Segnala</button>
        <?php if(isUserAdmin()): ?>
        <button class="btn-pin-post" data-id="<?php echo $post["idpost"]; ?>" data-pinned="<?php echo isset($post['pinned']) && $post['pinned'] ? 1 : 0; ?>">
            <?php echo (isset($post['pinned']) && $post['pinned']) ? '📌 Unpin' : '📌 Pin'; ?>
        </button>
        <?php endif; ?>
        <button class="condividi-btn" data-post-id="<?php echo $post["idpost"]; ?>">🔗 Condividi</button>

        <?php if(isUserAdmin()): ?>
        <button class="admin-delete-btn" onclick="deletePost(<?php echo $post['idpost']; ?>)">🗑️ Elimina</button>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <p>
    <?php 
    $tags = $dbh->getTagsByPostId($post["idpost"]);
    if(count($tags) > 0): 
        echo "[";
        foreach($tags as $i => $tag){
            if($i > 0) echo ", ";
            echo $tag["nometag"];
        }
        echo "]";
    else:
        echo "[Nessun Tag]";
    endif;
    ?>
    </p>
    <section>
        <h4>Commenti</h4>
        <?php 
        $commenti = $dbh->getCommentsByPostId($post["idpost"]);
        if(count($commenti) > 0):
            foreach($commenti as $commento): 
                // Determine commenter display name and link
                $commenterName = $commento["nomeautore"];
                $commenterId = $commento["idutente"];
                $commenterIsAdmin = isset($commento["amministratore"]) && $commento["amministratore"];
                $commenterBadge = $commenterIsAdmin ? ' <span class="admin-badge" title="Amministratore">👑</span>' : '';
        ?>
        <article class="comment" id="comment-<?php echo $commento['idcommento']; ?>">
            <p>
                <strong>
                    <?php if($commenterId): ?>
                    <a href="profilo.php?id=<?php echo $commenterId; ?>" class="author-link"><?php echo $commenterName; ?></a><?php echo $commenterBadge; ?>
                    <?php else: ?>
                    <?php echo $commenterName; ?>
                    <?php endif; ?>
                </strong>: 
                <?php echo $commento["testocommento"]; ?> 
                <small><?php echo $commento["datacommento"]; ?></small>
                <?php if(isUserLoggedIn()): ?>
                <button class="segnala-btn icon-only" title="Segnala commento" aria-label="Segnala commento" data-idcommento="<?php echo $commento['idcommento']; ?>" data-idpost="<?php echo $post['idpost']; ?>">⚠️</button>
                <?php endif; ?>
                <?php if(isUserAdmin()): ?>
                <button class="admin-delete-comment-btn" onclick="deleteComment(<?php echo $commento['idcommento']; ?>)">🗑️</button>
                <?php endif; ?>
            </p>
        </article>
        <?php 
            endforeach;
        else: 
        ?>
        <article>
            <p>Nessun commento</p>
        </article>
        <?php endif; ?>
        <?php if(isUserLoggedIn()): ?>
        <form action="processa-commento.php" method="POST">
            <input type="hidden" name="idpost" value="<?php echo $post["idpost"]; ?>" />
            <fieldset>
                <legend>Lascia un commento</legend>
                <textarea name="testocommento" required placeholder="Scrivi qui..."></textarea>
                <button type="submit" name="submit">Invia</button>
            </fieldset>
        </form>
        <?php else: ?>
        <p class="login-message"><a href="login.php">Accedi</a> per interagire con questo post</p>
        <?php endif; ?>
    </section>
</article>
<?php endif; ?>

<script>
function deletePost(idpost) {
    if(confirm('Sei sicuro di voler eliminare questo post?')) {
        fetch('api-admin.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=deletePost&id=' + idpost
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.href = 'index.php';
            } else {
                alert(data.error || 'Errore durante l\'eliminazione');
            }
        });
    }
}

function deleteComment(idcommento) {
    if(confirm('Sei sicuro di voler eliminare questo commento?')) {
        fetch('api-admin.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=deleteComment&id=' + idcommento
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.error || 'Errore durante l\'eliminazione');
            }
        });
    }
}
</script>
<script>
function togglePinPost(idpost, pinned) {
    const newPinned = pinned ? 0 : 1;
    if(!confirm(newPinned ? 'Vuoi pinnare questo post?' : 'Vuoi rimuovere il pin da questo post?')) return;
    fetch('api-admin.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=pin_post&idpost=' + idpost + '&pinned=' + newPinned
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || (data.success ? 'Operazione completata' : 'Errore'));
        if(data.success) location.reload();
    })
    .catch(err => alert('Errore nella richiesta'));
}

// Attach click handler to pin button if present (fallback when admin.js not loaded)
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.btn-pin-post');
    if(btn){
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-id');
            const pinned = this.getAttribute('data-pinned') === '1' ? 1 : 0;
            togglePinPost(id, pinned);
        });
    }
});
</script>
