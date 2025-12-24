<?php
require_once 'bootstrap.php';

$idpost = -1;
if(isset($_GET["id"])){
    $idpost = $_GET["id"];
}

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Post";
$templateParams["nome"] = "singolo-post.php";
//Post Template
$templateParams["post"] = $dbh->getPostById($idpost);

require 'template/base.php';
?>
