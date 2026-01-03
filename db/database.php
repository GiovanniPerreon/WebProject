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

    // Get latest posts (with anonymous support)
    public function getPosts($n=-1){
        $query = "SELECT p.idpost, p.titolopost, p.imgpost, p.anteprimapost, p.datapost, p.likes, p.anonimo, p.utente as idutente,
                  CASE WHEN p.anonimo = 1 THEN 'Anonimo' ELSE u.nome END as nome,
                  u.amministratore
                  FROM post p
                  JOIN utente u ON p.utente = u.idutente
                  ORDER BY p.datapost DESC";
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
        $query = "SELECT p.idpost, p.titolopost, p.imgpost, p.testopost, p.anteprimapost, p.datapost, p.likes, p.anonimo, p.utente as idutente,
                  u.nome, u.amministratore
                  FROM post p
                  JOIN utente u ON p.utente = u.idutente
                  WHERE p.idpost=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get posts by user ID
    public function getPostByUserId($id){
        $query = "SELECT idpost, titolopost, imgpost, anteprimapost, datapost, likes, anonimo FROM post WHERE utente=? ORDER BY datapost DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Insert a new post (with anonymous option)
    public function insertPost($titolopost, $testopost, $anteprimapost, $datapost, $imgpost, $utente, $anonimo = 0){
        $query = "INSERT INTO post (titolopost, testopost, anteprimapost, datapost, imgpost, utente, anonimo) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssii', $titolopost, $testopost, $anteprimapost, $datapost, $imgpost, $utente, $anonimo);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Update a post of a specific user
    public function updatePostOfUser($idpost, $titolopost, $testopost, $anteprimapost, $imgpost, $utente, $anonimo = 0){
        $query = "UPDATE post SET titolopost = ?, testopost = ?, anteprimapost = ?, imgpost = ?, anonimo = ?
                  WHERE idpost = ? AND utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssiis', $titolopost, $testopost, $anteprimapost, $imgpost, $anonimo, $idpost, $utente);
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

    // Delete any post (admin only)
    public function deletePost($idpost){
        $query = "DELETE FROM post WHERE idpost = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Get all users
    public function getUsers(){
        $query = "SELECT idutente, username, nome, attivo, amministratore FROM utente";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get user by ID
    public function getUserById($id){
        $query = "SELECT idutente, username, nome, imgprofilo, amministratore FROM utente WHERE idutente=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Check login (with admin and profile image support)
    public function checkLogin($username, $password){
        $query = "SELECT idutente, username, nome, amministratore, imgprofilo FROM utente WHERE attivo=1 AND username=? AND password=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Check if username exists
    public function usernameExists($username){
        $query = "SELECT COUNT(*) as count FROM utente WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    // Register new user
    public function registerUser($username, $password, $nome){
        $query = "INSERT INTO utente (username, password, nome, attivo, amministratore, imgprofilo) VALUES (?, ?, ?, 1, 0, 'default-avatar.png')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sss', $username, $password, $nome);
        return $stmt->execute();
    }

    // Update user profile image
    public function updateUserProfileImage($idutente, $imgprofilo){
        $query = "UPDATE utente SET imgprofilo = ? WHERE idutente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $imgprofilo, $idutente);
        return $stmt->execute();
    }

    // Count user posts
    public function countUserPosts($idutente){
        $query = "SELECT COUNT(*) as count FROM post WHERE utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Count user total likes received
    public function countUserLikesReceived($idutente){
        $query = "SELECT COALESCE(SUM(likes), 0) as total FROM post WHERE utente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idutente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Get comments by post ID with user info (for clickable profiles and admin badges)
    public function getCommentsByPostId($idpost){
        $query = "SELECT c.idcommento, c.testocommento, c.datacommento, c.nomeautore, 
                         c.utente AS idutente, u.nome, u.amministratore
                  FROM commento c
                  LEFT JOIN utente u ON c.utente = u.idutente
                  WHERE c.post=? 
                  ORDER BY c.datacommento ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Insert a new comment (with optional user ID for logged in users)
    public function insertComment($testocommento, $nomeautore, $idpost, $idutente = null){
        $query = "INSERT INTO commento (testocommento, datacommento, nomeautore, post, utente) 
                  VALUES (?, NOW(), ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssii', $testocommento, $nomeautore, $idpost, $idutente);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Delete any comment (admin only)
    public function deleteComment($idcommento){
        $query = "DELETE FROM commento WHERE idcommento = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idcommento);
        $stmt->execute();
        return $stmt->affected_rows > 0;
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

    // Insert new tag (admin only)
    public function insertTag($nometag){
        $query = "INSERT INTO tag (nometag) VALUES (?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $nometag);
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        return false;
    }

    // Update tag (admin only)
    public function updateTag($idtag, $nometag){
        $query = "UPDATE tag SET nometag = ? WHERE idtag = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $nometag, $idtag);
        return $stmt->execute();
    }

    // Delete tag (admin only)
    public function deleteTag($idtag){
        $query = "DELETE FROM tag WHERE idtag = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idtag);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Get posts by tag (with anonymous support)
    public function getPostsByTag($idtag, $n=-1){
        $query = "SELECT p.idpost, p.titolopost, p.imgpost, p.anteprimapost, p.datapost, p.likes, p.anonimo, p.utente as idutente,
                  CASE WHEN p.anonimo = 1 THEN 'Anonimo' ELSE u.nome END as nome,
                  u.amministratore
                  FROM post p
                  JOIN utente u ON p.utente = u.idutente
                  JOIN post_tag pt ON pt.post = p.idpost
                  WHERE pt.tag = ?
                  ORDER BY p.datapost DESC";
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

    // ==================== SEGNALAZIONI ====================

    // Insert a report
    public function insertSegnalazione($motivo, $descrizione, $idpost, $idcommento, $utente_segnalante, $utente_segnalato){
        $query = "INSERT INTO segnalazione (motivo, descrizione, datasegnalazione, post, commento, utente_segnalante, utente_segnalato) 
                  VALUES (?, ?, NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssiiii', $motivo, $descrizione, $idpost, $idcommento, $utente_segnalante, $utente_segnalato);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Get all reports (for admin)
    public function getSegnalazioni($stato = null){
        $query = "SELECT s.*, 
                  p.titolopost, 
                  c.testocommento,
                  u1.nome as nome_segnalante,
                  u2.nome as nome_segnalato
                  FROM segnalazione s
                  LEFT JOIN post p ON s.post = p.idpost
                  LEFT JOIN commento c ON s.commento = c.idcommento
                  LEFT JOIN utente u1 ON s.utente_segnalante = u1.idutente
                  LEFT JOIN utente u2 ON s.utente_segnalato = u2.idutente";
        if($stato !== null){
            $query .= " WHERE s.stato = ?";
        }
        $query .= " ORDER BY s.datasegnalazione DESC";
        $stmt = $this->db->prepare($query);
        if($stato !== null){
            $stmt->bind_param('s', $stato);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update report status
    public function updateSegnalazioneStato($idsegnalazione, $stato){
        $query = "UPDATE segnalazione SET stato = ? WHERE idsegnalazione = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $stato, $idsegnalazione);
        return $stmt->execute();
    }

    // Count pending reports
    public function countPendingSegnalazioni(){
        $query = "SELECT COUNT(*) as count FROM segnalazione WHERE stato = 'pending'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Get post owner ID
    public function getPostOwnerId($idpost){
        $query = "SELECT utente FROM post WHERE idpost = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idpost);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['utente'] : null;
    }
}
?>
