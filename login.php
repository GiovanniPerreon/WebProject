<?php
require_once 'bootstrap.php';

if(isUserLoggedIn()){
    header("location: profilo.php");
    exit;
}

$templateParams["titolo"] = "Spotted Unibo Cesena - Login";
$templateParams["nome"] = "login-form.php";

if(isset($_POST["login"])){
    $login_result = $dbh->checkLogin($_POST["username"], $_POST["password"]);
    if(count($login_result)==0){
        $templateParams["errorelogin"] = "Errore! Controllare username o password!";
    }
    else{
        registerLoggedUser($login_result[0]);
        header("location: profilo.php");
        exit;
    }
}

require 'template/base.php';
?>
