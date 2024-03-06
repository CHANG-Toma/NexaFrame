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
        <div class="error <?php echo isset($message) ? '' : 'hidden'; ?>">
            <?php
            if (isset($message)) {
                echo $message;
                unset($message);
            }
            ?>
            <br>
        </div>
        <form action="/installer/account" method="post">
            <div class="form-group">
                <label for="domainName">Nom du site :</label>
                <input type="text" id="domainName" name="domain-name" required>
            </div>
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required>
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