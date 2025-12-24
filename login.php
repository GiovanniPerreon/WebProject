<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Login";
$templateParams["nome"] = isUserLoggedIn() ? "login-home.php" : "login-form.php";

//Check login
if(isset($_POST["login"])){
    $login_result = $dbh->checkLogin($_POST["username"], $_POST["password"]);
    if(count($login_result)==0){
        //Login failed
        $templateParams["errorelogin"] = "Errore! Controllare username o password!";
    }
    else{
        registerLoggedUser($login_result[0]);
        $templateParams["nome"] = "login-home.php";
    }
}

require 'template/base.php';
?>
