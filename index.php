<?php
require_once 'bootstrap.php';

// Paginazione
$postsPerPage = 20;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $postsPerPage;

// Get total posts for pagination
$totalPosts = $dbh->countTotalPosts();
$totalPages = ceil($totalPosts / $postsPerPage);

//Base Template
$templateParams["titolo"] = "Spotted Unibo Cesena - Home";
$templateParams["nome"] = "lista-posts.php";
$templateParams["posts"] = $dbh->getPosts($postsPerPage, $offset);
$templateParams["currentPage"] = $currentPage;
$templateParams["totalPages"] = $totalPages;
$templateParams["baseUrl"] = "index.php";

require 'template/base.php';
?>
