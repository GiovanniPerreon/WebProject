<?php
require_once 'bootstrap.php';

// Set JSON header
header('Content-Type: application/json');

// Get action parameter
$action = $_GET['action'] ?? '';

/**
 * Get posts filtered by tag
 */
if ($action === 'getByTag') {
    $idtag = isset($_GET['idtag']) ? intval($_GET['idtag']) : -1;

    try {
        // Fetch posts by tag or without tags
        if ($idtag == 0) {
            // Get posts without tags
            $posts = $dbh->getPostsWithoutTags(-1);
            $tagName = 'Senza Tag';
        } else {
            // Get posts by specific tag
            $posts = $dbh->getPostsByTag($idtag);
            
            // Get tag name for the response
            $tagName = '';
            if ($idtag > 0) {
                $tags = $dbh->getTags();
                foreach ($tags as $tag) {
                    if ($tag['idtag'] == $idtag) {
                        $tagName = $tag['nometag'];
                        break;
                    }
                }
            }
        }

        // For each post, get its tags
        foreach ($posts as &$post) {
            $post['tags'] = $dbh->getTagsByPostId($post['idpost']);
        }

        echo json_encode([
            'success' => true,
            'posts' => $posts,
            'tagName' => $tagName,
            'tagId' => $idtag,
            'count' => count($posts)
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Errore nel caricamento dei post'
        ]);
        exit;
    }
}

/**
 * Get all posts (homepage)
 */
if ($action === 'getAll') {
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

    try {
        $posts = $dbh->getPosts($limit);

        // For each post, get its tags
        foreach ($posts as &$post) {
            $post['tags'] = $dbh->getTagsByPostId($post['idpost']);
        }

        echo json_encode([
            'success' => true,
            'posts' => $posts,
            'count' => count($posts)
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Errore nel caricamento dei post'
        ]);
        exit;
    }
}

// Invalid action
echo json_encode([
    'success' => false,
    'error' => 'Azione non valida'
]);
?>
