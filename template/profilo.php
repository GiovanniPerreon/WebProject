<div class="profilo-container">
    <div class="profilo-header">
        <div class="profilo-avatar">
            <?php 
            $imgProfilo = !empty($templateParams["user"]["imgprofilo"]) ? $templateParams["user"]["imgprofilo"] : "default-avatar.png";
            ?>
            <img src="<?php echo UPLOAD_DIR.$imgProfilo; ?>" alt="Avatar di <?php echo $templateParams["user"]["nome"]; ?>" />
            <?php if($templateParams["isOwnProfile"]): ?>
            <form action="profilo.php" method="POST" enctype="multipart/form-data" class="avatar-form">
                <label for="imgprofilo" class="avatar-edit-btn" title="Cambia immagine profilo">📷</label>
                <input type="file" id="imgprofilo" name="imgprofilo" accept="image/*" onchange="this.form.submit();" />
                <input type="hidden" name="update_profile_image" value="1" />
            </form>
            <?php endif; ?>
        </div>
        <div class="profilo-info">
            <h2>
                <?php echo $templateParams["user"]["nome"]; ?>
                <?php if($templateParams["user"]["amministratore"]): ?>
                <span class="admin-badge" title="Amministratore">👑</span>
                <?php endif; ?>
            </h2>
            <?php if($templateParams["isOwnProfile"]): ?>
            <p class="username">@<?php echo $_SESSION["username"]; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($templateParams["messaggio"])): ?>
    <div class="alert alert-success"><?php echo $templateParams["messaggio"]; ?></div>
    <?php endif; ?>
    
    <?php if(isset($templateParams["errore"])): ?>
    <div class="alert alert-error"><?php echo $templateParams["errore"]; ?></div>
    <?php endif; ?>

    <div class="profilo-stats">
        <div class="stat">
            <span class="stat-number"><?php echo $templateParams["postCount"]; ?></span>
            <span class="stat-label">Post</span>
        </div>
    </div>

    <?php if($templateParams["isOwnProfile"]): ?>
    <div class="profilo-actions">
        <a href="gestisci-posts.php?azione=1" class="btn btn-primary">➕ Nuovo Post</a>
        <a href="logout.php" class="btn btn-secondary">🚪 Logout</a>
        <?php if(isUserAdmin()): ?>
        <a href="admin.php" class="btn btn-admin">⚙️ Pannello Amministratore</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="profilo-actions">
        <a href="messaggi.php?user=<?php echo $templateParams["user"]["idutente"]; ?>" class="btn btn-primary">💬 Invia Messaggio</a>
    </div>
    <?php endif; ?>
</div>

<h2><?php echo $templateParams["isOwnProfile"] ? "I tuoi Post" : "Post di " . $templateParams["user"]["nome"]; ?></h2>
<section class="profilo-posts">
    <?php if(count($templateParams["posts"]) == 0): ?>
        <?php if($templateParams["isOwnProfile"]): ?>
        <p class="no-posts">Non hai ancora creato nessun post. <a href="gestisci-posts.php?azione=1">Crea il tuo primo post!</a></p>
        <?php else: ?>
        <p class="no-posts">Questo utente non ha ancora creato nessun post.</p>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach($templateParams["posts"] as $post): ?>
        <article class="post-item-profilo">
            <div class="post-image">
                <?php if(!empty($post["imgpost"])): ?>
                <img src="<?php echo UPLOAD_DIR.$post["imgpost"]; ?>" alt="<?php echo $post["titolopost"]; ?>" />
                <?php endif; ?>
            </div>
            <div class="post-content">
                <h3>
                    <?php echo $post["titolopost"]; ?>
                    <?php if(isset($post["anonimo"]) && $post["anonimo"]): ?>
                    <span class="anonimo-badge" title="Post anonimo">🎭</span>
                    <?php endif; ?>
                </h3>
                <p class="post-preview"><?php echo $post["anteprimapost"]; ?></p>
                <p class="post-meta">
                    <span class="post-date">📅 <?php echo $post["datapost"]; ?></span>
                </p>
                <div class="post-actions-profilo">
                    <a href="post.php?id=<?php echo $post["idpost"]; ?>" class="btn-small btn-view">👁️ Vedi</a>
                    <?php if($templateParams["isOwnProfile"]): ?>
                    <a href="gestisci-posts.php?azione=2&id=<?php echo $post["idpost"]; ?>" class="btn-small btn-edit">✏️ Modifica</a>
                    <a href="gestisci-posts.php?azione=3&id=<?php echo $post["idpost"]; ?>" class="btn-small btn-delete">🗑️ Elimina</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
