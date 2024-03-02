<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Installer - Admin Account</title>
</head>

<body>
    <div class="installer-container">
        <h2>Create Admin Account</h2>
        <?= $message ?>
        <form action="/admin/create" method="POST">
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
                <label for="password">Repeat password:</label>
                <input type="password" id="repeat-password" name="repeat-password" required>
            </div>
            <button class="Button Primary" type="submit">Create Account</button>
        </form>
    </div>
</body>

</html>