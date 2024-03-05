<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/dist/css/main.css" />
    <title>Installer - BDD</title>   
</head>
<body>
    <section class="form-container">
        <form id="dbTestForm" action="/installer/process" method="post">
        <?php if (isset($error)) { ?>
            <p class="error">
                <?= $error ?>
            </p>
        <?php } ?>
            <h2>Configuration de votre base de données</h2>
            <div class="form-group">
                <label for="dbHost">Hôte :</label>
                <input type="text" id="dbHost" name="db_host" placeholder="ex : postgres" required>
            </div>
            <div class="form-group">
                <label for="dbPort">Port :</label>
                <input type="text" id="dbPort" name="db_port" placeholder="ex : 5432" required>
            </div>
            <div class="form-group">
                <label for="dbName">Nom de la Base de Données :</label>
                <input type="text" id="dbName" name="db_name" placeholder="ex : db_name" required>
            </div>
            <div class="form-group">
                <label for="dbUser">Utilisateur :</label>
                <input type="text" id="dbUser" name="db_user" placeholder="ex : root" required>
            </div>
            <div class="form-group">
                <label for="dbPassword">Mot de Passe :</label>
                <input type="password" id="dbPassword" name="db_password" placeholder="ex : root" required>
            </div>
            <div class="form-group">
                <label for="dbType">Type de Base de Données :</label>
                <select id="dbType" name="db_type" required>
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlsrv">SQL Server</option>
                    <option value="oci">Oracle</option>
                </select>
            </div>
            <button class="Button Primary" type="submit">Vérifier la connexion</button>
        </form>
    </section>
</body>
</html>
