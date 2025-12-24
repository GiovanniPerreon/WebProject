<?php if(count($templateParams["post"]) == 0): ?>
<h2>Post non trovato</h2>
<section>
    <p>Il post richiesto non esiste!</p>
</section>
<?php else: ?>
<h2><?php echo $templateParams["post"][0]["titolopost"]; ?></h2>
<article>
    <?php if(!empty($templateParams["post"][0]["imgpost"])): ?>
    <img src="<?php echo UPLOAD_DIR.$templateParams["post"][0]["imgpost"]; ?>" alt="<?php echo $templateParams["post"][0]["titolopost"]; ?>" />
    <?php endif; ?>
    <p><?php echo $templateParams["post"][0]["testopost"]; ?></p>
    <p><?php echo $templateParams["post"][0]["datapost"]; ?> - <?php echo $templateParams["post"][0]["nome"]; ?></p>
    <?php if(isUserLoggedIn()): 
        $hasLiked = $dbh->hasUserLikedPost($_SESSION['idutente'], $templateParams["post"][0]["idpost"]);
    ?>
    <div class="post-actions">
        <form action="processa-like.php" method="POST" style="display:inline;">
            <input type="hidden" name="idpost" value="<?php echo $templateParams["post"][0]["idpost"]; ?>" />
            <button type="submit" class="like-btn <?php echo $hasLiked ? 'liked' : ''; ?>">
                <?php echo $hasLiked ? '❤️' : '🤍'; ?> Like (<?php echo $templateParams["post"][0]["likes"]; ?>)
            </button>
        </form>
        <button class="segnala-btn">⚠️ Segnala</button>
        <button class="condividi-btn">🔗 Condividi</button>
    </div>
    <?php endif; ?>
    <p>
    <?php 
    $tags = $dbh->getTagsByPostId($templateParams["post"][0]["idpost"]);
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
        $commenti = $dbh->getCommentsByPostId($templateParams["post"][0]["idpost"]);
        if(count($commenti) > 0):
            foreach($commenti as $commento): 
        ?>
        <article>
            <p><?php echo $commento["nomeautore"]; ?>: <?php echo $commento["testocommento"]; ?> - <?php echo $commento["datacommento"]; ?></p>
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
            <input type="hidden" name="idpost" value="<?php echo $templateParams["post"][0]["idpost"]; ?>" />
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
