<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Installer - Forgot password</title>
</head>

<body>
    <section class="form-container">
        <h1>NexaFrame</h1>
        <h2>Mot de passe oublié</h2>

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

        <form method="post" action="/installer/forgot-password">
            <div class="form-group">
                <label for="email">Adresse e-mail :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <button class="Button Primary" type="submit">Envoyer</button>
            <div class="links">
                <a href="/installer/login" class="link">Se connecter</a>
            </div>
        </form>
    </section>
</body>

</html>