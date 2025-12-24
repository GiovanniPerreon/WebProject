<h2>Benvenuto <?php echo $_SESSION["nome"]; ?>!</h2>
<section>
    <p>Sei collegato come: <strong><?php echo $_SESSION["username"]; ?></strong></p>
    <p>
        <a href="gestisci-posts.php">Gestisci Post</a>
        <a href="logout.php">Logout</a>
    </p>
</section>
