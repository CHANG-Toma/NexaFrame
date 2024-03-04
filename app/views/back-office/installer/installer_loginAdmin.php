<!DOCTYPE html>
<html>
<head>
    <title>Connexion Administrateur</title>
</head>
<body>
    <h1>Connexion Administrateur</h1>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="/installer/login">
        <label for="login">Nom d'utilisateur:</label>
        <input type="text" name="login" id="login" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Se connecter">
    </form>
</body>
</html>