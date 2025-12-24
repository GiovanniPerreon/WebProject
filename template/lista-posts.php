<h2>Ultimi Post</h2>
<?php foreach($templateParams["posts"] as $post): ?>
<article>
    <h3><?php echo $post["titolopost"]; ?></h3>
    <?php if(!empty($post["imgpost"])): ?>
    <img src="<?php echo UPLOAD_DIR.$post["imgpost"]; ?>" alt="<?php echo $post["titolopost"]; ?>" />
    <?php endif; ?>
    <p><?php echo $post["anteprimapost"]; ?></p>
    <p><?php echo $post["datapost"]; ?> - <?php echo $post["nome"]; ?></p>
    <?php if(isUserLoggedIn()): 
        $hasLiked = $dbh->hasUserLikedPost($_SESSION['idutente'], $post["idpost"]);
    ?>
    <div class="post-actions">
        <form action="processa-like.php" method="POST" style="display:inline;">
            <input type="hidden" name="idpost" value="<?php echo $post["idpost"]; ?>" />
            <button type="submit" class="like-btn <?php echo $hasLiked ? 'liked' : ''; ?>">
                <?php echo $hasLiked ? '❤️' : '🤍'; ?> Like (<?php echo $post["likes"]; ?>)
            </button>
        </form>
        <button class="segnala-btn">⚠️ Segnala</button>
        <button class="condividi-btn">🔗 Condividi</button>
    </div>
    <?php endif; ?>
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
<?php endforeach; ?>
