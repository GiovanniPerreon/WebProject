<h2>Ultimi Post</h2>
<?php foreach($templateParams["posts"] as $post): ?>
<article class="post-preview">
    <h3>
        <a href="post.php?id=<?php echo $post["idpost"]; ?>" class="post-title-link">
            <?php echo $post["titolopost"]; ?>
        </a>
        <?php if(isset($post["anonimo"]) && $post["anonimo"] && isUserAdmin()): ?>
        <span class="admin-anonimo-badge" title="Post anonimo - Solo admin può vedere l'autore">🎭</span>
        <?php endif; ?>
    </h3>
    
    <?php if(!empty($post["imgpost"])): ?>
    <a href="post.php?id=<?php echo $post["idpost"]; ?>">
        <img src="<?php echo UPLOAD_DIR.$post["imgpost"]; ?>" alt="<?php echo $post["titolopost"]; ?>" />
    </a>
    <?php endif; ?>
    
    <p class="post-meta">
        <?php echo $post["datapost"]; ?> - 
        <?php 
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
        
        if($linkToProfile && isset($post["idutente"])):
        ?>
        <a href="profilo.php?id=<?php echo $post["idutente"]; ?>" class="author-link"><?php echo $displayName; ?></a><?php echo $adminBadge; ?>
        <?php else: ?>
        <?php echo $displayName; ?><?php echo $adminBadge; ?>
        <?php endif; ?>
    </p>
    
    <p class="post-tags">
    <?php 
    $tags = $dbh->getTagsByPostId($post["idpost"]);
    if(count($tags) > 0): 
        foreach($tags as $i => $tag):
            if($i > 0) echo " ";
    ?>
        <a href="tag.php?id=<?php echo $tag["idtag"]; ?>" class="tag-link"><?php echo $tag["nometag"]; ?></a>
    <?php 
        endforeach;
    endif;
    ?>
    </p>
</article>
<?php endforeach; ?>
