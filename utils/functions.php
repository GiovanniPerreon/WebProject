<?php
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF'])==$pagename){
        echo " class='active' ";
    }
}

function getIdFromName($name){
    return preg_replace("/[^a-z]/", '', strtolower($name));
}

function isUserLoggedIn(){
    return !empty($_SESSION['idutente']);
}

function isUserAdmin(){
    return !empty($_SESSION['amministratore']) && $_SESSION['amministratore'] == 1;
}

function registerLoggedUser($user){
    $_SESSION["idutente"] = $user["idutente"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["nome"] = $user["nome"];
    $_SESSION["amministratore"] = isset($user["amministratore"]) ? $user["amministratore"] : 0;
    $_SESSION["imgprofilo"] = isset($user["imgprofilo"]) ? $user["imgprofilo"] : "default-avatar.png";
}

function getEmptyPost(){
    return array("idpost" => "", "titolopost" => "", "imgpost" => "", "testopost" => "", "anteprimapost" => "", "anonimo" => 0);
}

function getAction($action){
    $result = "";
    switch($action){
        case 1:
            $result = "Inserisci";
            break;
        case 2:
            $result = "Modifica";
            break;
        case 3:
            $result = "Cancella";
            break;
    }

    return $result;
}

function uploadImage($path, $image){
    $imageName = basename($image["name"]);
    $fullPath = $path.$imageName;
    
    $maxKB = 500;
    $acceptedExtensions = array("jpg", "jpeg", "png", "gif");
    $result = 0;
    $msg = "";
    
    //Controllo se immagine è OK
    $imageSize = $image["size"];
    if($imageSize == 0){
        $msg .= "Errore nell'upload dell'immagine </br>";
        $result = -1;
    }
    else{
        //Controllo dimensione < 500KB
        $imageSize = $imageSize / 1024;
        if($imageSize > $maxKB){
            $msg .= "L'immagine è troppo grande. Dimensione massima: ".$maxKB." KB. Dimensione corrente: ".$imageSize." KB.</br>";
            $result = -1;
        }

        //Controllo estensione del file
        $imageFileType = strtolower(pathinfo($fullPath,PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $acceptedExtensions)){
            $msg .= "Sono accettate solo le seguenti estensioni: ".implode(",", $acceptedExtensions)."</br>";
            $result = -1;
        }

        //Se il file esiste già, riutilizzalo invece di caricarne uno nuovo
        if (file_exists($fullPath)) {
            $result = 1;
            return array("result"=>$result, "msg"=>"Immagine esistente riutilizzata", "name"=>$imageName);
        }
    }

    //Se i controlli sono passati, sposto l'immagine dalla posizione temporanea alla cartella di destinazione
    if($result == 0){
        if(!move_uploaded_file($image["tmp_name"], $fullPath)){
            $msg .= "Errore nel caricamento dell'immagine </br>";
            $result = -1;
        }
        else{
            $result = 1;
        }
    }
    
    return array("result"=>$result, "msg"=>$msg, "name"=>$imageName);
}

function getStatoSegnalazioneLabel($stato){
    switch($stato){
        case 'pending': return 'In attesa';
        case 'reviewed': return 'In revisione';
        case 'resolved': return 'Risolto';
        case 'dismissed': return 'Respinto';
        default: return $stato;
    }
}

function getStatoSegnalazioneClass($stato){
    switch($stato){
        case 'pending': return 'stato-pending';
        case 'reviewed': return 'stato-reviewed';
        case 'resolved': return 'stato-resolved';
        case 'dismissed': return 'stato-dismissed';
        default: return '';
    }
}
?>
