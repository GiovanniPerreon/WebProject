<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Home";
$templateParams["nome"] = "lista-posts.php";
//Home Template
$templateParams["posts"] = $dbh->getPosts(10);

require 'template/base.php';
?>
