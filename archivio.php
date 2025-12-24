<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Archivio";
$templateParams["nome"] = "lista-posts.php";
//Archivio Template
$templateParams["posts"] = $dbh->getPosts(-1);

require 'template/base.php';
?>
