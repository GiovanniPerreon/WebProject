<?php
require_once 'bootstrap.php';

if(isUserLoggedIn()){
    header("location: profilo.php");
    exit;
}

$templateParams["titolo"] = "Spotted Unibo Cesena - Registrazione";
$templateParams["nome"] = "registrati-form.php";

if(isset($_POST["registrati"])){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $conferma_password = trim($_POST["conferma_password"]);
    $nome = trim($_POST["nome"]);
    
    // Validazione
    $errori = array();
    
    if(empty($username) || strlen($username) < 3){
        $errori[] = "L'username deve essere di almeno 3 caratteri";
    }
    
    if(empty($password) || strlen($password) < 6){
        $errori[] = "La password deve essere di almeno 6 caratteri";
    }
    
    if($password !== $conferma_password){
        $errori[] = "Le password non coincidono";
    }
    
    if(empty($nome)){
        $errori[] = "Il nome visualizzato è obbligatorio";
    }
    
    // Controlla se l'username esiste già
    if(empty($errori) && $dbh->usernameExists($username)){
        $errori[] = "Username già in uso, scegline un altro";
    }
    
    if(empty($errori)){
        // Registra l'utente
        $result = $dbh->registerUser($username, $password, $nome);
        if($result){
            $templateParams["successo"] = "Registrazione completata! Ora puoi effettuare il login.";
        } else {
            $templateParams["erroreregistrazione"] = "Errore durante la registrazione. Riprova.";
        }
    } else {
        $templateParams["erroreregistrazione"] = implode("<br>", $errori);
    }
}

require 'template/base.php';
?>
