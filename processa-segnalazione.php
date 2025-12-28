<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

if(!isUserLoggedIn()){
    echo json_encode(["success" => false, "message" => "Devi essere loggato per segnalare"]);
    exit;
}

$motivo = isset($_POST["motivo"]) ? trim($_POST["motivo"]) : "";
$descrizione = isset($_POST["descrizione"]) ? trim($_POST["descrizione"]) : "";
$idpost = isset($_POST["idpost"]) ? intval($_POST["idpost"]) : null;
$idcommento = isset($_POST["idcommento"]) ? intval($_POST["idcommento"]) : null;

if(empty($motivo)){
    echo json_encode(["success" => false, "message" => "Specifica il motivo della segnalazione"]);
    exit;
}

if(empty($idpost) && empty($idcommento)){
    echo json_encode(["success" => false, "message" => "Devi specificare cosa vuoi segnalare"]);
    exit;
}

$utente_segnalante = $_SESSION["idutente"];
$utente_segnalato = null;

if($idpost){
    $utente_segnalato = $dbh->getPostOwnerId($idpost);
}

$result = $dbh->insertSegnalazione(
    $motivo,
    $descrizione,
    $idpost ? $idpost : null,
    $idcommento ? $idcommento : null,
    $utente_segnalante,
    $utente_segnalato
);

if($result){
    echo json_encode(["success" => true, "message" => "Segnalazione inviata con successo. Grazie per il tuo contributo!"]);
} else {
    echo json_encode(["success" => false, "message" => "Errore nell'invio della segnalazione"]);
}
?>
