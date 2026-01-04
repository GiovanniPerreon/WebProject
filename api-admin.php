<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

if(!isUserLoggedIn() || !isUserAdmin()){
    echo json_encode(["success" => false, "error" => "Non autorizzato"]);
    exit;
}

$action = isset($_POST["action"]) ? $_POST["action"] : "";

switch($action){
    case "deletePost":
    case "delete_post":
        $idpost = intval(isset($_POST["id"]) ? $_POST["id"] : (isset($_POST["idpost"]) ? $_POST["idpost"] : 0));
        $result = $dbh->deletePost($idpost);
        echo json_encode(["success" => $result, "message" => $result ? "Post eliminato" : "Errore nell'eliminazione"]);
        break;
        
    case "deleteComment":
    case "delete_comment":
        $idcommento = intval(isset($_POST["id"]) ? $_POST["id"] : (isset($_POST["idcommento"]) ? $_POST["idcommento"] : 0));
        $result = $dbh->deleteComment($idcommento);
        echo json_encode(["success" => $result, "message" => $result ? "Commento eliminato" : "Errore nell'eliminazione"]);
        break;
        
    case "add_tag":
        $nometag = trim($_POST["nometag"]);
        if(empty($nometag)){
            echo json_encode(["success" => false, "error" => "Nome tag vuoto"]);
            break;
        }
        $result = $dbh->insertTag($nometag);
        if($result){
            echo json_encode(["success" => true, "message" => "Tag aggiunto", "idtag" => $result]);
        } else {
            echo json_encode(["success" => false, "error" => "Errore nell'aggiunta (tag già esistente?)"]);
        }
        break;
        
    case "update_tag":
        $idtag = intval($_POST["idtag"]);
        $nometag = trim($_POST["nometag"]);
        if(empty($nometag)){
            echo json_encode(["success" => false, "error" => "Nome tag vuoto"]);
            break;
        }
        $result = $dbh->updateTag($idtag, $nometag);
        echo json_encode(["success" => $result, "message" => $result ? "Tag aggiornato" : "Errore nell'aggiornamento"]);
        break;
        
    case "delete_tag":
        $idtag = intval($_POST["idtag"]);
        $result = $dbh->deleteTag($idtag);
        echo json_encode(["success" => $result, "message" => $result ? "Tag eliminato" : "Errore nell'eliminazione"]);
        break;

    case 'pin_post':
        $idpost = intval(isset($_POST['idpost']) ? $_POST['idpost'] : 0);
        $pinned = intval(isset($_POST['pinned']) ? $_POST['pinned'] : 0);
        $result = $dbh->setPostPinned($idpost, $pinned);
        echo json_encode(["success" => $result, "message" => $result ? ($pinned ? "Post pinnato" : "Post unpinnato") : "Errore durante l'operazione"]);
        break;
        
    case "update_segnalazione":
        $idsegnalazione = intval($_POST["idsegnalazione"]);
        $stato = $_POST["stato"];
        $validStates = ['pending', 'reviewed', 'resolved', 'dismissed'];
        if(!in_array($stato, $validStates)){
            echo json_encode(["success" => false, "error" => "Stato non valido"]);
            break;
        }
        $result = $dbh->updateSegnalazioneStato($idsegnalazione, $stato);
        echo json_encode(["success" => $result, "message" => $result ? "Stato aggiornato" : "Errore nell'aggiornamento"]);
        break;
        
    default:
        echo json_encode(["success" => false, "error" => "Azione non riconosciuta"]);
        break;
}
?>
