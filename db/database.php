<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    // Get random posts
    public function getRandomPosts($n){
        $stmt = $this->db->prepare("SELECT idpost, titolopost, imgpost FROM post ORDER BY RAND() LIMIT ?");
        $stmt->bind_param('i', $n);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get latest posts
    public function getPosts($n=-1){
        $query = "SELECT idpost, titolopost, imgpost, anteprimapost, datapost, likes, nome 
                  FROM post, utente 
                  WHERE utente=idutente 
                  ORDER BY datapost DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($n > 0){
            $stmt->bind_param('i', $n);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get a post by ID
    public function getPostById($id){
        $query = "SELECT idpost, titolopost, imgpost, testopost, anteprimapost, datapost, likes, nome 
                  FROM post, utente 
                  WHERE idpost=? AND utente=idutente";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get posts by user ID
    public function getPostByUserId($id){
        $query = "SELECT idpost, titolopost, imgpost FROM post WHERE utente=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Insert a new post
    public function insertPost($titolopost, $testopost, $anteprimapost, $datapost, $imgpost, $utente){
        $query = "INSERT INTO post (titolopost, testopost, anteprimapost, datapost, imgpost, utente) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssi', $titolopost, $testopost, $anteprimapost, $datapost, $imgpost, $utente);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Update a post of a specific user
    public function updatePostOfUser($idpost, $titolopost, $testopost, $anteprimapost, $imgpost, $utente){
        $query = "UPDATE post SET titolopost = ?, testopost = ?, anteprimapost = ?, imgpost = ? 
                  WHERE idpost = ? AND utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssii', $titolopost, $testopost, $anteprimapost, $imgpost, $idpost, $utente);
        return $stmt->execute();
    }

    // Delete a post of a specific user
    public function deletePostOfUser($idpost, $utente){
        $query = "DELETE FROM post WHERE idpost = ? AND utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idpost, $utente);
        $stmt->execute();
        return true;
    }

    // Get all users
    public function getUsers(){
        $query = "SELECT idutente, username, nome, attivo FROM utente";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Check login
    public function checkLogin($username, $password){
        $query = "SELECT idutente, username, nome FROM utente WHERE attivo=1 AND username=? AND password=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get comments by post ID
    public function getCommentsByPostId($idpost){
        $query = "SELECT idcommento, testocommento, datacommento, nomeautore 
                  FROM commento 
                  WHERE post=? 
                  ORDER BY datacommento ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Insert a new comment
    public function insertComment($testocommento, $nomeautore, $idpost){
        $query = "INSERT INTO commento (testocommento, datacommento, nomeautore, post) 
                  VALUES (?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssi', $testocommento, $nomeautore, $idpost);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Get all tags
    public function getTags(){
        $query = "SELECT idtag, nometag FROM tag ORDER BY nometag ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get tags for a specific post
    public function getTagsByPostId($idpost){
        $query = "SELECT tag.idtag, tag.nometag 
                  FROM tag, post_tag 
                  WHERE post_tag.post=? AND post_tag.tag=tag.idtag";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add tag to post
    public function addTagToPost($idpost, $idtag){
        $query = "INSERT INTO post_tag (post, tag) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idpost, $idtag);
        return $stmt->execute();
    }

    // Remove all tags from post
    public function removeAllTagsFromPost($idpost){
        $query = "DELETE FROM post_tag WHERE post=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        return $stmt->execute();
    }

    // Get posts by tag
    public function getPostsByTag($idtag, $n=-1){
        $query = "SELECT post.idpost, post.titolopost, post.imgpost, post.anteprimapost, post.datapost, post.likes, utente.nome 
                  FROM post, utente, post_tag 
                  WHERE post.utente=utente.idutente AND post_tag.post=post.idpost AND post_tag.tag=? 
                  ORDER BY post.datapost DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($n > 0){
            $stmt->bind_param('ii', $idtag, $n);
        } else {
            $stmt->bind_param('i', $idtag);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Check if user has liked a post
    public function hasUserLikedPost($idutente, $idpost){
        $query = "SELECT COUNT(*) as count FROM user_likes WHERE utente = ? AND post = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idutente, $idpost);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Toggle like (add if not exists, remove if exists)
    public function toggleLike($idutente, $idpost){
        if($this->hasUserLikedPost($idutente, $idpost)){
            // Remove like
            $query = "DELETE FROM user_likes WHERE utente = ? AND post = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idutente, $idpost);
            $stmt->execute();
            
            // Decrement likes count
            $query2 = "UPDATE post SET likes = likes - 1 WHERE idpost = ?";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bind_param('i', $idpost);
            $stmt2->execute();
            
            return false; // Unliked
        } else {
            // Add like
            $query = "INSERT INTO user_likes (utente, post) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('ii', $idutente, $idpost);
            $stmt->execute();
            
            // Increment likes count
            $query2 = "UPDATE post SET likes = likes + 1 WHERE idpost = ?";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bind_param('i', $idpost);
            $stmt2->execute();
            
            return true; // Liked
        }
    }
}
?>
