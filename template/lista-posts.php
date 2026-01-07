<h2>Ultimi Post</h2>
<?php foreach($templateParams["posts"] as $post): ?>
<article class="post-preview">
    <h3>
        <a href="post.php?id=<?php echo $post["idpost"]; ?>" class="post-title-link">
            <?php echo $post["titolopost"]; ?>
        </a>
        <?php if(isset($post['pinned']) && $post['pinned']): ?>
        <span class="pinned-badge" title="Post pinnato">📌</span>
        <?php endif; ?>
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

<?php if(isset($templateParams["totalPages"]) && $templateParams["totalPages"] > 1): ?>
<nav class="pagination">
    <?php 
    $currentPage = $templateParams["currentPage"];
    $totalPages = $templateParams["totalPages"];
    $baseUrl = isset($templateParams["baseUrl"]) ? $templateParams["baseUrl"] : "index.php";
    
    // Previous button
    if($currentPage > 1):
    ?>
    <a href="<?php echo $baseUrl; ?>?page=<?php echo $currentPage - 1; ?>" class="pagination-btn">&laquo; Precedente</a>
    <?php else: ?>
    <span class="pagination-btn disabled">&laquo; Precedente</span>
    <?php endif; ?>
    
    <?php
    // Page numbers
    $range = 2; // Show 2 pages on each side
    $start = max(1, $currentPage - $range);
    $end = min($totalPages, $currentPage + $range);
    
    // First page
    if($start > 1):
    ?>
    <a href="<?php echo $baseUrl; ?>?page=1" class="pagination-number">1</a>
    <?php if($start > 2): ?>
    <span class="pagination-ellipsis">...</span>
    <?php endif; ?>
    <?php endif; ?>
    
    <?php
    // Middle pages
    for($i = $start; $i <= $end; $i++):
        if($i == $currentPage):
    ?>
    <span class="pagination-number active"><?php echo $i; ?></span>
    <?php else: ?>
    <a href="<?php echo $baseUrl; ?>?page=<?php echo $i; ?>" class="pagination-number"><?php echo $i; ?></a>
    <?php 
        endif;
    endfor;
    ?>
    
    <?php
    // Last page
    if($end < $totalPages):
        if($end < $totalPages - 1):
    ?>
    <span class="pagination-ellipsis">...</span>
    <?php endif; ?>
    <a href="<?php echo $baseUrl; ?>?page=<?php echo $totalPages; ?>" class="pagination-number"><?php echo $totalPages; ?></a>
    <?php endif; ?>
    
    <?php
    // Next button
    if($currentPage < $totalPages):
    ?>
    <a href="<?php echo $baseUrl; ?>?page=<?php echo $currentPage + 1; ?>" class="pagination-btn">Successivo &raquo;</a>
    <?php else: ?>
    <span class="pagination-btn disabled">Successivo &raquo;</span>
    <?php endif; ?>
</nav>
<?php endif; ?>
