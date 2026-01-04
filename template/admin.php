<div class="admin-container">
    <h2>⚙️ Pannello Amministrazione</h2>
    
    <nav class="admin-nav">
        <a href="admin.php" class="<?php echo $templateParams["view"] == "dashboard" ? "active" : ""; ?>">📊 Dashboard</a>
        <a href="admin.php?view=segnalazioni" class="<?php echo $templateParams["view"] == "segnalazioni" ? "active" : ""; ?>">
            🚨 Segnalazioni
            <?php 
            $pending = $dbh->countPendingSegnalazioni();
            if($pending > 0): 
            ?>
            <span class="badge"><?php echo $pending; ?></span>
            <?php endif; ?>
        </a>
        <a href="admin.php?view=tags" class="<?php echo $templateParams["view"] == "tags" ? "active" : ""; ?>">🏷️ Tag</a>
    </nav>

    <div class="admin-content">
        <?php if($templateParams["view"] == "dashboard"): ?>
        <!-- DASHBOARD -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <span class="card-icon">🚨</span>
                <span class="card-number"><?php echo $templateParams["pendingSegnalazioni"]; ?></span>
                <span class="card-label">Segnalazioni in attesa</span>
                <a href="admin.php?view=segnalazioni&stato=pending" class="card-link">Vedi tutte</a>
            </div>
            <div class="dashboard-card">
                <span class="card-icon">🏷️</span>
                <span class="card-number"><?php echo $templateParams["totalTags"]; ?></span>
                <span class="card-label">Tag</span>
                <a href="admin.php?view=tags" class="card-link">Gestisci</a>
            </div>
        </div>

        <?php elseif($templateParams["view"] == "segnalazioni"): ?>
        <!-- SEGNALAZIONI -->
        <div class="admin-section">
            <h3>🚨 Gestione Segnalazioni</h3>
            
            <div class="filter-bar">
                <a href="admin.php?view=segnalazioni" class="filter-btn <?php echo !isset($templateParams["filtro_stato"]) ? 'active' : ''; ?>">Tutte</a>
                <a href="admin.php?view=segnalazioni&stato=pending" class="filter-btn <?php echo (isset($templateParams["filtro_stato"]) && $templateParams["filtro_stato"] == 'pending') ? 'active' : ''; ?>">In attesa</a>
                <a href="admin.php?view=segnalazioni&stato=reviewed" class="filter-btn <?php echo (isset($templateParams["filtro_stato"]) && $templateParams["filtro_stato"] == 'reviewed') ? 'active' : ''; ?>">In revisione</a>
                <a href="admin.php?view=segnalazioni&stato=resolved" class="filter-btn <?php echo (isset($templateParams["filtro_stato"]) && $templateParams["filtro_stato"] == 'resolved') ? 'active' : ''; ?>">Risolte</a>
                <a href="admin.php?view=segnalazioni&stato=dismissed" class="filter-btn <?php echo (isset($templateParams["filtro_stato"]) && $templateParams["filtro_stato"] == 'dismissed') ? 'active' : ''; ?>">Respinte</a>
            </div>
            
            <?php if(count($templateParams["segnalazioni"]) == 0): ?>
            <p class="empty-message">Nessuna segnalazione trovata.</p>
            <?php else: ?>
            <div class="segnalazioni-list">
                <?php foreach($templateParams["segnalazioni"] as $segnalazione): ?>
                <article class="segnalazione-item <?php echo getStatoSegnalazioneClass($segnalazione["stato"]); ?>">
                    <div class="segnalazione-header">
                        <span class="segnalazione-motivo"><?php echo $segnalazione["motivo"]; ?></span>
                        <span class="segnalazione-stato <?php echo getStatoSegnalazioneClass($segnalazione["stato"]); ?>">
                            <?php echo getStatoSegnalazioneLabel($segnalazione["stato"]); ?>
                        </span>
                    </div>
                    <div class="segnalazione-body">
                        <?php if($segnalazione["descrizione"]): ?>
                        <p class="segnalazione-desc"><?php echo $segnalazione["descrizione"]; ?></p>
                        <?php endif; ?>
                        <p class="segnalazione-meta">
                            📅 <?php echo $segnalazione["datasegnalazione"]; ?>
                            | 👤 Segnalato da: <?php echo $segnalazione["nome_segnalante"]; ?>
                            <?php if($segnalazione["nome_segnalato"]): ?>
                            | ⚠️ Utente segnalato: <?php echo $segnalazione["nome_segnalato"]; ?>
                            <?php endif; ?>
                        </p>
                        <?php if($segnalazione["post"]): ?>
                        <p class="segnalazione-ref">📝 Post: <a href="post.php?id=<?php echo $segnalazione["post"]; ?>"><?php echo $segnalazione["titolopost"]; ?></a></p>
                        <?php endif; ?>
                        <?php if($segnalazione["commento"]):
                            $commentPostId = $segnalazione["post"] ? $segnalazione["post"] : (isset($segnalazione["comment_post"]) ? $segnalazione["comment_post"] : null);
                        ?>
                        <p class="segnalazione-ref">💬 Commento: "<?php echo substr($segnalazione["testocommento"], 0, 100); ?>..." 
                        <?php if($commentPostId): ?>
                            <a href="post.php?id=<?php echo $commentPostId; ?>#comment-<?php echo $segnalazione["commento"]; ?>">(Vai al commento)</a>
                        <?php endif; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="segnalazione-actions">
                        <select class="stato-select" data-id="<?php echo $segnalazione["idsegnalazione"]; ?>">
                            <option value="pending" <?php echo $segnalazione["stato"] == 'pending' ? 'selected' : ''; ?>>In attesa</option>
                            <option value="reviewed" <?php echo $segnalazione["stato"] == 'reviewed' ? 'selected' : ''; ?>>In revisione</option>
                            <option value="resolved" <?php echo $segnalazione["stato"] == 'resolved' ? 'selected' : ''; ?>>Risolto</option>
                            <option value="dismissed" <?php echo $segnalazione["stato"] == 'dismissed' ? 'selected' : ''; ?>>Respinto</option>
                        </select>
                        <?php if($segnalazione["post"]): ?>
                        <button class="btn-admin-action btn-delete-post" data-id="<?php echo $segnalazione["post"]; ?>">🗑️ Elimina Post</button>
                        <?php endif; ?>
                        <?php if($segnalazione["commento"]): ?>
                        <button class="btn-admin-action btn-delete-comment" data-id="<?php echo $segnalazione["commento"]; ?>">🗑️ Elimina Commento</button>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php elseif($templateParams["view"] == "tags"): ?>
        <!-- TAGS -->
        <div class="admin-section">
            <h3>🏷️ Gestione Tag</h3>
            
            <form class="add-tag-form" id="addTagForm">
                <input type="text" id="newTagName" placeholder="Nome nuovo tag..." required />
                <button type="submit" class="btn btn-primary">➕ Aggiungi Tag</button>
            </form>
            
            <div class="tags-list">
                <?php foreach($templateParams["tags"] as $tag): ?>
                <div class="tag-item" data-id="<?php echo $tag["idtag"]; ?>">
                    <input type="text" class="tag-name-input" value="<?php echo $tag["nometag"]; ?>" />
                    <div class="tag-actions">
                        <button class="btn-small btn-save-tag" title="Salva">💾</button>
                        <button class="btn-small btn-delete-tag" title="Elimina">🗑️</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>
