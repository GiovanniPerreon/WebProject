<!DOCTYPE html>
<html lang="it">
<head>
    <title><?php echo $templateParams["titolo"]; ?></title>
    <link rel="stylesheet" href="./css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</head>
<body>
    <header>
        <h1>Spotted Unibo Cesena</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="contatti.php">Contatti</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
    <main>
    <?php
    if(isset($templateParams["nome"])){
        require($templateParams["nome"]);
    }
    ?>
    </main>
    <aside>
        <section>
            <h2>Bacheca</h2>
            <ul>
                <?php 
                $tags = $dbh->getTags();
                foreach($tags as $tag): 
                ?>
                <li><a href="tag.php?id=<?php echo $tag["idtag"]; ?>"><?php echo $tag["nometag"]; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </aside>
    <footer>
        <p>Spotted Unibo Cesena</p>
    </footer>
</body>
</html>
