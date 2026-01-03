<h2>Registrazione</h2>
<?php if(isset($templateParams["erroreregistrazione"])): ?>
<section class="error-message">
    <p><?php echo $templateParams["erroreregistrazione"]; ?></p>
</section>
<?php endif; ?>

<?php if(isset($templateParams["successo"])): ?>
<section class="success-message">
    <p><?php echo $templateParams["successo"]; ?></p>
    <p><a href="login.php" class="btn btn-primary">Vai al Login</a></p>
</section>
<?php else: ?>
<form action="registrati.php" method="POST">
    <fieldset>
        <legend>Crea il tuo account</legend>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required minlength="3" 
               placeholder="Almeno 3 caratteri" 
               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        
        <label for="nome">Nome visualizzato:</label>
        <input type="text" id="nome" name="nome" required 
               placeholder="Come vuoi essere chiamato"
               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required minlength="6" 
               placeholder="Almeno 6 caratteri">
        
        <label for="conferma_password">Conferma Password:</label>
        <input type="password" id="conferma_password" name="conferma_password" required minlength="6" 
               placeholder="Ripeti la password">
        
        <button type="submit" name="registrati" class="btn btn-primary">Registrati</button>
    </fieldset>
</form>

<p class="form-footer">
    Hai già un account? <a href="login.php">Accedi qui</a>
</p>
<?php endif; ?>
