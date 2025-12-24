<h2>Login</h2>
<?php if(isset($templateParams["errorelogin"])): ?>
<section>
    <p><?php echo $templateParams["errorelogin"]; ?></p>
</section>
<?php endif; ?>
<form action="login.php" method="POST">
    <ul>
        <li>
            <label for="username">Username:</label><input type="text" id="username" name="username" />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" />
        </li>
        <li>
            <input type="submit" name="login" value="Invia" />
        </li>
    </ul>
</form>
