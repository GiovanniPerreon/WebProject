<?php
require_once 'bootstrap.php';

// Check if user is logged in
if(!isUserLoggedIn()) {
    header('Location: login.php');
    exit();
}

if(isset($_POST['idpost'])) {
    $idpost = $_POST['idpost'];
    $idutente = $_SESSION['idutente'];
    
    // Toggle like
    $dbh->toggleLike($idutente, $idpost);
    
    // Redirect back to previous page
    if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: index.php');
    }
    exit();
}

// If no idpost, redirect to home
header('Location: index.php');
exit();
?>
