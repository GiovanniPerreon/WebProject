<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

// Check if user is logged in
if(!isUserLoggedIn()){
    echo json_encode(array("error" => "Non autorizzato"));
    exit;
}

$idutente = $_SESSION['idutente'];

// Handle different actions
if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'sendMessage':
            if(isset($_POST['destinatario']) && isset($_POST['messaggio'])){
                $destinatario = intval($_POST['destinatario']);
                $messaggio = trim($_POST['messaggio']);
                
                if(empty($messaggio)){
                    echo json_encode(array("error" => "Il messaggio non può essere vuoto"));
                    exit;
                }
                
                if($destinatario == $idutente){
                    header('Location: messaggi.php?user=' . $destinatario . '&error=self');
                    exit;
                }
                
                if($dbh->sendMessage($idutente, $destinatario, $messaggio)){
                    header('Location: messaggi.php?user=' . $destinatario . '&success=1');
                    exit;
                } else {
                    header('Location: messaggi.php?user=' . $destinatario . '&error=send');
                    exit;
                }
            } else {
                header('Location: messaggi.php?error=missing');
                exit;
            }
            break;
            
        case 'getMessages':
            if(isset($_GET['user'])){
                $otherUser = intval($_GET['user']);
                $messages = $dbh->getMessagesBetweenUsers($idutente, $otherUser);
                // Mark as read
                $dbh->markMessagesAsRead($otherUser, $idutente);
                echo json_encode($messages);
            } else {
                echo json_encode(array("error" => "Utente non specificato"));
            }
            break;
            
        case 'getUnreadCount':
            $count = $dbh->getUnreadMessageCount($idutente);
            echo json_encode(array("count" => $count));
            break;
            
        default:
            echo json_encode(array("error" => "Azione non valida"));
    }
} else {
    echo json_encode(array("error" => "Nessuna azione specificata"));
}
?>
