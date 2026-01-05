<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

// Check if user is logged in
if(!isUserLoggedIn()){
    echo json_encode(array("success" => false, "error" => "Non autorizzato"));
    exit;
}

$idutente = $_SESSION['idutente'];

// Handle GET requests
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['action'])){
        switch($_GET['action']){
            case 'getConversation':
                // Get conversation with a specific user
                if(isset($_GET['user'])){
                    $otherUserId = intval($_GET['user']);

                    // Get user info
                    $user = $dbh->getUserById($otherUserId);

                    if(!$user){
                        echo json_encode(array("success" => false, "error" => "Utente non trovato"));
                        exit;
                    }

                    // Get messages
                    $messages = $dbh->getMessagesBetweenUsers($idutente, $otherUserId);

                    // Mark messages as read
                    $dbh->markMessagesAsRead($otherUserId, $idutente);

                    echo json_encode(array(
                        "success" => true,
                        "user" => $user,
                        "messages" => $messages
                    ));
                } else {
                    echo json_encode(array("success" => false, "error" => "Utente non specificato"));
                }
                break;

            case 'getNewMessages':
                // Get new messages since last check (for polling)
                if(isset($_GET['user']) && isset($_GET['since'])){
                    $otherUserId = intval($_GET['user']);
                    $sinceId = intval($_GET['since']);

                    // Get only new messages after $sinceId
                    $allMessages = $dbh->getMessagesBetweenUsers($idutente, $otherUserId);

                    // Filter messages newer than $sinceId
                    $newMessages = array_filter($allMessages, function($msg) use ($sinceId) {
                        return $msg['idmessaggio'] > $sinceId;
                    });

                    // Re-index array
                    $newMessages = array_values($newMessages);

                    echo json_encode(array(
                        "success" => true,
                        "messages" => $newMessages,
                        "count" => count($newMessages)
                    ));
                } else {
                    echo json_encode(array("success" => false, "error" => "Parametri mancanti"));
                }
                break;

            case 'getUnreadCount':
                // Get total unread message count
                $count = $dbh->getUnreadMessageCount($idutente);
                echo json_encode(array(
                    "success" => true,
                    "count" => $count
                ));
                break;

            default:
                echo json_encode(array("success" => false, "error" => "Azione GET non valida"));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Nessuna azione specificata"));
    }
    exit;
}

// Handle POST requests
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['action'])){
        switch($_POST['action']){
            case 'sendMessage':
                if(isset($_POST['destinatario']) && isset($_POST['messaggio'])){
                    $destinatario = intval($_POST['destinatario']);
                    $messaggio = trim($_POST['messaggio']);

                    if(empty($messaggio)){
                        echo json_encode(array("success" => false, "error" => "Il messaggio non può essere vuoto"));
                        exit;
                    }

                    if($destinatario == $idutente){
                        echo json_encode(array("success" => false, "error" => "Non puoi mandare messaggi a te stesso"));
                        exit;
                    }

                    // Send message
                    $result = $dbh->sendMessage($idutente, $destinatario, $messaggio);

                    if($result){
                        // Get the message we just sent (for returning to client)
                        $messages = $dbh->getMessagesBetweenUsers($idutente, $destinatario);
                        $lastMessage = end($messages);

                        echo json_encode(array(
                            "success" => true,
                            "message" => $lastMessage,
                            "timestamp" => date('Y-m-d H:i:s')
                        ));
                    } else {
                        echo json_encode(array("success" => false, "error" => "Errore nell'invio del messaggio"));
                    }
                } else {
                    echo json_encode(array("success" => false, "error" => "Parametri mancanti"));
                }
                break;

            case 'markAsRead':
                if(isset($_POST['user'])){
                    $otherUserId = intval($_POST['user']);

                    // Mark messages from this user as read
                    $result = $dbh->markMessagesAsRead($otherUserId, $idutente);

                    // Get updated unread count
                    $unreadCount = $dbh->getUnreadMessageCount($idutente);

                    echo json_encode(array(
                        "success" => true,
                        "unreadCount" => $unreadCount
                    ));
                } else {
                    echo json_encode(array("success" => false, "error" => "Parametri mancanti"));
                }
                break;

            default:
                echo json_encode(array("success" => false, "error" => "Azione POST non valida"));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Nessuna azione specificata"));
    }
    exit;
}

// Invalid request method
echo json_encode(array("success" => false, "error" => "Metodo HTTP non valido"));
?>
