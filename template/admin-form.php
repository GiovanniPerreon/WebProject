<h2><?php echo getAction($templateParams["azione"]); ?> Post</h2>
<?php if(isset($templateParams["errore"])): ?>
<section>
    <p><?php echo $templateParams["errore"]; ?></p>
</section>
<?php endif; ?>
    
<form action="processa-post.php" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Dati del Post</legend>
        
        <input type="hidden" name="idpost" value="<?php echo $templateParams["post"]["idpost"]; ?>" />
        <input type="hidden" name="azione" value="<?php echo $templateParams["azione"]; ?>" />
        
        <label for="titolopost">Titolo:</label>
        <input type="text" id="titolopost" name="titolopost" 
               value="<?php echo isset($templateParams["post"]["titolopost"]) ? $templateParams["post"]["titolopost"] : ''; ?>" 
               <?php if($templateParams["azione"]==3){echo "readonly";} ?> required />
        
        <label for="anteprimapost">Anteprima:</label>
        <textarea id="anteprimapost" name="anteprimapost" rows="3" 
                  <?php if($templateParams["azione"]==3){echo "readonly";} ?> required><?php echo isset($templateParams["post"]["anteprimapost"]) ? $templateParams["post"]["anteprimapost"] : ''; ?></textarea>
        
        <label for="testopost">Testo:</label>
        <textarea id="testopost" name="testopost" rows="8" 
                  <?php if($templateParams["azione"]==3){echo "readonly";} ?> required><?php echo isset($templateParams["post"]["testopost"]) ? $templateParams["post"]["testopost"] : ''; ?></textarea>
        
        <?php if($templateParams["azione"]!=3): ?>
            <label>Tag:</label>
            <?php 
            $allTags = $dbh->getTags();
            $postTags = isset($templateParams["post"]["idpost"]) && $templateParams["post"]["idpost"] != "" ? $dbh->getTagsByPostId($templateParams["post"]["idpost"]) : array();
            $postTagIds = array_column($postTags, 'idtag');
            foreach($allTags as $tag): 
            ?>
            <div class="tag-checkbox">
                <input type="checkbox" id="tag_<?php echo $tag["idtag"]; ?>" name="tags[]" value="<?php echo $tag["idtag"]; ?>" 
                       <?php if(in_array($tag["idtag"], $postTagIds)){echo "checked";} ?> />
                <label for="tag_<?php echo $tag["idtag"]; ?>"><?php echo $tag["nometag"]; ?></label>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if($templateParams["azione"]!=3): ?>
            <label for="imgpost">Immagine:</label>
            <div class="file-input-wrapper">
                <input type="file" id="imgpost" name="imgpost" accept="image/*" 
                       <?php if($templateParams["azione"]==1){echo "required";} ?> />
                <label for="imgpost" class="file-input-label">
                    <span class="file-icon">📁</span>
                    <span class="file-text">Scegli un'immagine</span>
                </label>
                <span class="file-name" id="file-name">Nessun file selezionato</span>
            </div>
            <?php if(!empty($templateParams["post"]["imgpost"])): ?>
                <p class="current-image">
                    Immagine corrente: <?php echo $templateParams["post"]["imgpost"]; ?>
                    <img src="<?php echo UPLOAD_DIR.$templateParams["post"]["imgpost"]; ?>" alt="Preview" class="image-preview" />
                </p>
            <?php endif; ?>
        <?php endif; ?>
        
        <button type="submit"><?php echo getAction($templateParams["azione"]); ?></button>
    </fieldset>
</form>

<script>
document.getElementById('imgpost').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Nessun file selezionato';
    document.getElementById('file-name').textContent = fileName;
    document.querySelector('.file-input-label .file-text').textContent = e.target.files[0] ? 'Cambia immagine' : 'Scegli un\'immagine';
});
</script>
