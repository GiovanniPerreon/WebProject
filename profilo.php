<?php
require_once 'bootstrap.php';

$viewingOther = isset($_GET["id"]) && is_numeric($_GET["id"]);
$profileUserId = $viewingOther ? intval($_GET["id"]) : (isUserLoggedIn() ? $_SESSION["idutente"] : null);

if(!$profileUserId){
    header("location: login.php");
    exit;
}

$profileUser = $dbh->getUserById($profileUserId);
if(!$profileUser){
    header("location: index.php");
    exit;
}

$templateParams["titolo"] = "Spotted Unibo Cesena - Profilo di " . $profileUser["nome"];

$isOwnProfile = isUserLoggedIn() && $_SESSION["idutente"] == $profileUserId;

$templateParams["user"] = $profileUser;
$templateParams["isOwnProfile"] = $isOwnProfile;
$templateParams["posts"] = $dbh->getPostByUserId($profileUserId);
$templateParams["postCount"] = $dbh->countUserPosts($profileUserId);
$templateParams["likesReceived"] = $dbh->countUserLikesReceived($profileUserId);

if($isOwnProfile && isset($_POST["update_profile_image"]) && isset($_FILES["imgprofilo"])){
    if(!empty($_FILES["imgprofilo"]["name"])){
        $uploadResult = uploadImage(UPLOAD_DIR, $_FILES["imgprofilo"]);
        if($uploadResult["result"] == 1){
            $dbh->updateUserProfileImage($profileUserId, $uploadResult["name"]);
            $_SESSION["imgprofilo"] = $uploadResult["name"];
            $templateParams["user"]["imgprofilo"] = $uploadResult["name"];
            $templateParams["messaggio"] = "Immagine profilo aggiornata con successo!";
        } else {
            $templateParams["errore"] = $uploadResult["msg"];
        }
    }
}

$templateParams["nome"] = "profilo.php";

require 'template/base.php';
?>
