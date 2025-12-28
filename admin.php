<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn() || !isUserAdmin()){
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "Spotted Unibo Cesena - Pannello Admin";
$templateParams["js"] = array("js/admin.js");

$view = isset($_GET["view"]) ? $_GET["view"] : "dashboard";
$templateParams["view"] = $view;

switch($view){
    case "tags":
        $templateParams["tags"] = $dbh->getTags();
        break;
    case "segnalazioni":
        $stato = isset($_GET["stato"]) ? $_GET["stato"] : null;
        $templateParams["segnalazioni"] = $dbh->getSegnalazioni($stato);
        $templateParams["filtro_stato"] = $stato;
        break;
    default: // dashboard
        $templateParams["pendingSegnalazioni"] = $dbh->countPendingSegnalazioni();
        $templateParams["totalTags"] = count($dbh->getTags());
        break;
}

$templateParams["nome"] = "admin.php";

require 'template/base.php';
?>
