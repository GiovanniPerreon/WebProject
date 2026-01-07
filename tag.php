<?php
require_once 'bootstrap.php';

$idtag = -1;
$nometag = "";
if(isset($_GET["id"])){
    $idtag = $_GET["id"];
    
    // Se id è 0, mostra i post senza tag
    if($idtag == 0){
        $nometag = "Senza Tag";
    } else {
        $tags = $dbh->getTags();
        foreach($tags as $tag){
            if($tag["idtag"] == $idtag){
                $nometag = $tag["nometag"];
                break;
            }
        }
    }
}

$templateParams["titolo"] = "Spotted Unibo Cesena - " . $nometag;
$templateParams["nome"] = "lista-posts.php";

// Ottieni i post in base al tag selezionato
if($idtag == 0){
    $templateParams["posts"] = $dbh->getPostsWithoutTags(-1);
} else {
    $templateParams["posts"] = $dbh->getPostsByTag($idtag);
}

require 'template/base.php';
?>
