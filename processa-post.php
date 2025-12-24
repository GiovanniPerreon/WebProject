<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
    exit;
}

$azione = $_POST["azione"];
$idpost = $_POST["idpost"];

switch($azione){
    case 1: //Inserimento
        $uploadResult = uploadImage(UPLOAD_DIR, $_FILES["imgpost"]);
        if($uploadResult["result"] == 1){
            $imgName = $uploadResult["name"];
            $newPostId = $dbh->insertPost(
                $_POST["titolopost"],
                $_POST["testopost"],
                $_POST["anteprimapost"],
                date("Y-m-d"),
                $imgName,
                $_SESSION["idutente"]
            );
            
            // Save tags
            if(isset($_POST["tags"]) && is_array($_POST["tags"])){
                foreach($_POST["tags"] as $tagId){
                    $dbh->addTagToPost($newPostId, $tagId);
                }
            }
            
            header("location: gestisci-posts.php");
        }
        else{
            //Upload error
            header("location: gestisci-posts.php?azione=1&errore=".urlencode($uploadResult["msg"]));
        }
        break;
    case 2: //Modifica
        // Get current post to preserve image if not uploading a new one
        $currentPostData = $dbh->getPostById($idpost);
        if(empty($currentPostData)){
            header("location: gestisci-posts.php?errore=Post non trovato");
            exit;
        }
        $currentPost = $currentPostData[0];
        $imgName = $currentPost["imgpost"];
        
        //Check if new image was uploaded
        if(!empty($_FILES["imgpost"]["name"])){
            $uploadResult = uploadImage(UPLOAD_DIR, $_FILES["imgpost"]);
            if($uploadResult["result"] == 1){
                $imgName = $uploadResult["name"];
                // Optional: delete old image if it's not default.jpg
                if($currentPost["imgpost"] != "default.jpg" && file_exists(UPLOAD_DIR.$currentPost["imgpost"])){
                    unlink(UPLOAD_DIR.$currentPost["imgpost"]);
                }
            }
            else{
                //Upload error
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
            $_SESSION["idutente"]
        );
        
        // Update tags
        $dbh->removeAllTagsFromPost($idpost);
        if(isset($_POST["tags"]) && is_array($_POST["tags"])){
            foreach($_POST["tags"] as $tagId){
                $dbh->addTagToPost($idpost, $tagId);
            }
        }
        
        header("location: gestisci-posts.php");
        break;
    case 3: //Cancellazione
        $dbh->deletePostOfUser($idpost, $_SESSION["idutente"]);
        header("location: gestisci-posts.php");
        break;
}
?>
