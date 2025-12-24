<?php
require_once 'bootstrap.php';

header('Content-Type: application/json');

$idpost = -1;
if(isset($_GET["id"])){
    $idpost = $_GET["id"];
}

$result = $dbh->getPostById($idpost);

echo json_encode($result);
?>
