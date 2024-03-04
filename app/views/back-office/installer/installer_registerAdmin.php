<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Installer - Admin Account</title>
</head>

<body>
    <section class="form-container">
        <h2>Create Admin Account</h2>
        <div class="error-message <?php echo isset($error) ? '' : 'hidden'; ?>">
            <?php
            if (isset($error)) {
                echo $error;
                unset($error);
            }
            ?>
            <br>
        </div>
        <form action="/installer/account" method="post">
            <?php if (isset($error)) { ?>
                <p class="error">
                    <?= $error ?>
                </p>
            <?php } ?>

            <div class="form-group">
                <label for="domainName">Nom de domaine :</label>
                <input type="text" id="domainName" name="domain-name" required>
            </div>
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Repeat password:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button class="Button Primary" type="submit">S'enregistrer</button>
        </form>
    </section>
</body>

</html>