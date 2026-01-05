<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

// Handle GET requests (some endpoints don't require login)
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['action'])){
        switch($_GET['action']){
            case 'getNewComments':
                // Get new comments since a specific comment ID
                if(isset($_GET['idpost']) && isset($_GET['since'])){
                    $idpost = intval($_GET['idpost']);
                    $sinceId = intval($_GET['since']);

                    // Get all comments for this post
                    $allComments = $dbh->getCommentsByPostId($idpost);

                    // Filter comments newer than $sinceId
                    $newComments = array_filter($allComments, function($comment) use ($sinceId) {
                        return $comment['idcommento'] > $sinceId;
                    });

                    // Re-index array
                    $newComments = array_values($newComments);

                    // Check if current user is admin (if logged in)
                    $isAdmin = isUserLoggedIn() ? isUserAdmin() : false;
                    $currentUserId = isUserLoggedIn() ? $_SESSION['idutente'] : null;

                    echo json_encode(array(
                        "success" => true,
                        "comments" => $newComments,
                        "isAdmin" => $isAdmin,
                        "currentUserId" => $currentUserId,
                        "count" => count($newComments)
                    ));
                } else {
                    echo json_encode(array("success" => false, "error" => "Parametri mancanti"));
                }
                break;

            case 'getMyNewComments':
                // Get new comments on current user's posts (global notifications)
                if(!isUserLoggedIn()){
                    echo json_encode(array("success" => false, "error" => "Non autorizzato"));
                    exit;
                }

                if(isset($_GET['since'])){
                    $since = $_GET['since'];
                    $currentUserId = $_SESSION['idutente'];

                    // Get all posts by current user
                    $userPosts = $dbh->getPostByUserId($currentUserId);

                    $newComments = array();

                    // For each post, get comments newer than the timestamp
                    foreach($userPosts as $post){
                        $postComments = $dbh->getCommentsByPostId($post['idpost']);

                        foreach($postComments as $comment){
                            // Only include comments after the timestamp and not by the current user
                            // Convert idutente to int for proper comparison (could be null)
                            $commentUserId = isset($comment['idutente']) ? intval($comment['idutente']) : null;

                            if($comment['datacommento'] > $since && $commentUserId != $currentUserId){
                                // Add post title to comment data
                                $comment['titolopost'] = $post['titolopost'];
                                $comment['idpost'] = $post['idpost'];
                                $newComments[] = $comment;
                            }
                        }
                    }

                    // Return current server time for next poll
                    $currentTime = date('Y-m-d H:i:s');

                    echo json_encode(array(
                        "success" => true,
                        "newComments" => $newComments,
                        "count" => count($newComments),
                        "currentTime" => $currentTime
                    ));
                } else {
                    echo json_encode(array("success" => false, "error" => "Parametri mancanti"));
                }
                break;

            default:
                echo json_encode(array("success" => false, "error" => "Azione GET non valida"));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Nessuna azione specificata"));
    }
    exit;
}

// Handle POST requests (require login)
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Check if user is logged in for POST requests
    if(!isUserLoggedIn()){
        echo json_encode(array("success" => false, "error" => "Non autorizzato"));
        exit;
    }

    $idutente = $_SESSION['idutente'];
    $nomeautore = $_SESSION['nome'];

    if(isset($_POST['action'])){
        switch($_POST['action']){
            case 'addComment':
                if(isset($_POST['idpost']) && isset($_POST['testocommento'])){
                    $idpost = intval($_POST['idpost']);
                    $testocommento = trim($_POST['testocommento']);

                    if(empty($testocommento)){
                        echo json_encode(array("success" => false, "error" => "Il commento non può essere vuoto"));
                        exit;
                    }

                    // Insert comment
                    $result = $dbh->insertComment($testocommento, $nomeautore, $idpost, $idutente);

                    if($result){
                        // Get the comment we just inserted
                        $comments = $dbh->getCommentsByPostId($idpost);
                        $lastComment = end($comments);

                        // Check if current user is admin
                        $isAdmin = isUserAdmin();

                        echo json_encode(array(
                            "success" => true,
                            "comment" => $lastComment,
                            "isAdmin" => $isAdmin,
                            "currentUserId" => $idutente,
                            "message" => "Commento aggiunto con successo"
                        ));
                    } else {
                        echo json_encode(array("success" => false, "error" => "Errore nell'aggiunta del commento"));
                    }
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
