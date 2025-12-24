<h2>I tuoi Post</h2>
<section>
    <p><a href="gestisci-posts.php?azione=1">Crea Nuovo Post</a></p>
    
    <?php if(count($templateParams["posts"]) == 0): ?>
        <p>Non hai ancora creato nessun post.</p>
    <?php else: ?>
        <?php foreach($templateParams["posts"] as $post): ?>
        <article class="post-item">
            <h3><?php echo $post["titolopost"]; ?></h3>
            <p>
                <a href="gestisci-posts.php?azione=2&id=<?php echo $post["idpost"]; ?>">Modifica</a>
                <a href="gestisci-posts.php?azione=3&id=<?php echo $post["idpost"]; ?>" class="delete-btn">Elimina</a>
            </p>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
