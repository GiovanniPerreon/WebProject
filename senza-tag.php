<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Post Senza Tag";
$templateParams["nome"] = "lista-posts.php";
$templateParams["posts"] = $dbh->getPostsWithoutTags(-1);

require 'template/base.php';
?>
