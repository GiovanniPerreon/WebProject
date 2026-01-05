<?php
require_once 'bootstrap.php';

// Check if user is logged in
if(!isUserLoggedIn()){
    header("Location: login.php");
    exit;
}

$idutente = $_SESSION['idutente'];

// Get conversation partner if specified
$conversationWith = isset($_GET['user']) ? intval($_GET['user']) : null;

// Get all conversations
$templateParams["conversations"] = $dbh->getConversations($idutente);

// If a specific conversation is selected
if($conversationWith){
    $templateParams["selectedUser"] = $dbh->getUserById($conversationWith);
    $templateParams["messages"] = $dbh->getMessagesBetweenUsers($idutente, $conversationWith);
    // Mark messages as read
    $dbh->markMessagesAsRead($conversationWith, $idutente);
}

$templateParams["titolo"] = "Messaggi - Spotted Unibo Cesena";
$templateParams["nome"] = "messages.php";
$templateParams["js"] = array("js/messages.js");

require 'template/base.php';
?>
