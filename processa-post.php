<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
    exit;
}

$azione = $_POST["azione"];
$idpost = $_POST["idpost"];
$anonimo = isset($_POST["anonimo"]) ? 1 : 0;

switch($azione){
    case 1:
        $uploadResult = uploadImage(UPLOAD_DIR, $_FILES["imgpost"]);
        if($uploadResult["result"] == 1){
            $imgName = $uploadResult["name"];
            $newPostId = $dbh->insertPost(
                $_POST["titolopost"],
                $_POST["testopost"],
                $_POST["anteprimapost"],
                date("Y-m-d"),
                $imgName,
                $_SESSION["idutente"],
                $anonimo
            );

            if(isset($_POST["tags"]) && is_array($_POST["tags"])){
                foreach($_POST["tags"] as $tagId){
                    $dbh->addTagToPost($newPostId, $tagId);
                }
            }
            
            header("location: gestisci-posts.php");
        }
        else{
            header("location: gestisci-posts.php?azione=1&errore=".urlencode($uploadResult["msg"]));
        }
        break;
    case 2:
        $currentPostData = $dbh->getPostById($idpost);
        if(empty($currentPostData)){
            header("location: gestisci-posts.php?errore=Post non trovato");
            exit;
        }
        $currentPost = $currentPostData[0];
        $imgName = $currentPost["imgpost"];

        if(!empty($_FILES["imgpost"]["name"])){
            $uploadResult = uploadImage(UPLOAD_DIR, $_FILES["imgpost"]);
            if($uploadResult["result"] == 1){
                $imgName = $uploadResult["name"];
                if($currentPost["imgpost"] != "default.jpg" && file_exists(UPLOAD_DIR.$currentPost["imgpost"])){
                    unlink(UPLOAD_DIR.$currentPost["imgpost"]);
                }
            }
            else{
                header("location: gestisci-posts.php?azione=2&id=".$idpost."&errore=".urlencode($uploadResult["msg"]));
                exit;
            }
        }
        
        $dbh->updatePostOfUser(
            $idpost,
            $_POST["titolopost"],
            $_POST["testopost"],
            $_POST["anteprimapost"],
            $imgName,
            $_SESSION["idutente"],
            $anonimo
        );

        $dbh->removeAllTagsFromPost($idpost);
        if(isset($_POST["tags"]) && is_array($_POST["tags"])){
            foreach($_POST["tags"] as $tagId){
                $dbh->addTagToPost($idpost, $tagId);
            }
        }
        
        header("location: gestisci-posts.php");
        break;
    case 3:
        $dbh->deletePostOfUser($idpost, $_SESSION["idutente"]);
        header("location: gestisci-posts.php");
        break;
}
?>
