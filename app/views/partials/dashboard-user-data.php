<section class="container-flex">
    <div class="user-info-container">
        <h2>Gestion de Mes Informations</h2>
        <div class="user-info-form">
            <form action="/dashboard/update-user" method="POST">

                <div class="error <?php echo isset($_SESSION["error_message"]) ? '' : 'hidden'; ?>">
                    <?php
                    if (isset($_SESSION['error_message'])) {
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    }
                    ?>
                </div>
                <div class="success <?php echo isset($_SESSION["success_message"]) ? '' : 'hidden'; ?>">
                    <?php
                    if (isset($_SESSION['success_message'])) {
                        echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']);
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="login">Login:</label>
                    <input type="text" id="login" name="login" value="<?= $firstname = "" ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $email = "" ?>">
                </div>
                <!-- Autres champs si nécessaire -->
                <button type="submit" class="btn-update">Mettre à jour</button>
            </form>
        </div>
    </div>

    <div class="password-update-container">
        <h2>Mettre à Jour le Mot de Passe</h2>
        <div class="password-update-form">
            <form action="/dashboard/update-password" method="POST">

                <div class="error <?php echo isset($_SESSION["error_message"]) ? '' : 'hidden'; ?>">
                    <?php
                    if (isset($_SESSION['error_message2'])) {
                        echo $_SESSION['error_message2'];
                        unset($_SESSION['error_message2']);
                    }
                    ?>
                </div>
                <div class="success <?php echo isset($_SESSION["success_message"]) ? '' : 'hidden'; ?>">
                    <?php
                    if (isset($_SESSION['success_message2'])) {
                        echo $_SESSION['success_message2'];
                        unset($_SESSION['success_message2']);
                    }
                    ?>
                </div>

                <div class="form-group">
                    <label for="currentPassword">Mot de Passe Actuel:</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Nouveau Mot de Passe:</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmer Nouveau Mot de Passe:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="btn-update">Mettre à Jour</button>
            </form>
        </div>
    </div>
</section>