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
        $query = "SELECT idpost, titolopost, imgpost, anteprimapost, datapost, nome 
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
        $query = "SELECT idpost, titolopost, imgpost, testopost, datapost, nome 
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
}
?>
