<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
    exit;
}

if(!isset($_POST["submit"]) || !isset($_POST["idpost"])){
    header("location: index.php");
    exit;
}

$idpost = $_POST["idpost"];
$nomeautore = $_SESSION["nome"];
$testocommento = $_POST["testocommento"];

// Insert comment
$dbh->insertComment($testocommento, $nomeautore, $idpost);

// Redirect back to the post
header("location: post.php?id=".$idpost);
?>
