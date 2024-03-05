<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Installer - Login</title>
</head>

<body>
    <section class="form-container">
        <h1 class="nexaframe">NexaFrame</h1>
        <h2>Connexion Admin</h2>

        <?php if (isset($error)) { ?>
            <p class="error">
                <?= $error ?>
            </p>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <p class="success">
                <?= $success ?>
            </p>
        <?php } ?>

        <form method="POST" action="/installer/login">
            <div class="form-group">
                <label for="login">Nom d'utilisateur:</label>
                <input type="text" name="login" id="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button class="Button Primary" type="submit">Se connecter</button>
            
            <div class="links">
                <a href="/installer/forgot-password" class="link">Mot de passe oublié ?</a>
            </div>
        </form>
    </section>
</body>

</html>