<?php
require_once 'bootstrap.php';

$idtag = -1;
$nometag = "";
if(isset($_GET["id"])){
    $idtag = $_GET["id"];
    $tags = $dbh->getTags();
    foreach($tags as $tag){
        if($tag["idtag"] == $idtag){
            $nometag = $tag["nometag"];
            break;
        }
    }
}

$templateParams["titolo"] = "Spotted Unibo Cesena - " . $nometag;
$templateParams["nome"] = "lista-posts.php";
$templateParams["posts"] = $dbh->getPostsByTag($idtag);

require 'template/base.php';
?>
