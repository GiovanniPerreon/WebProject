<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
    exit;
}

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Gestisci Post";

$azione = -1;
if(isset($_GET["azione"])){
    $azione = $_GET["azione"];
}

switch($azione){
    case 1: //Inserimento
    case 2: //Modifica
    case 3: //Cancellazione
        $templateParams["nome"] = "admin-form.php";
        $templateParams["azione"] = $azione;
        if($azione == 1){
            $templateParams["post"] = getEmptyPost();
        }
        else{
            $idpost = $_GET["id"];
            $templateParams["post"] = $dbh->getPostById($idpost)[0];
        }
        break;
    default:
        $templateParams["nome"] = "gestisci-posts-lista.php";
        $templateParams["posts"] = $dbh->getPostByUserId($_SESSION["idutente"]);
        break;
}

require 'template/base.php';
?>
