<?php
require_once 'bootstrap.php';

if(isUserLoggedIn()){
    session_destroy();
}

header("location: login.php");
?>
