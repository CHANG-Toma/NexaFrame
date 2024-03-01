<?php

namespace App\Controllers;

use App\Core\DB;

class installer
{
    public function __construct()
    {
        define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/front-office/main/installer_config.php';
    }

    public function processForm()
    {
        $dbConfig = [
            'DB_NAME' => $_POST['db_name'],
            'DB_USER' => $_POST['db_user'],
            'DB_PASSWORD' => $_POST['db_password'],
            'DB_HOST' => $_POST['db_host'],
            'DB_PORT' => $_POST['db_port'],
            'DB_TYPE' => $_POST['db_type'],
        ];

        $configContent = "<?php\n";
        foreach ($dbConfig as $key => $value) {
            $configContent .= "define('$key', '$value');\n";
        }

        file_put_contents('../app/config/config.php', $configContent);

        // Testez la connexion
        $db = new DB();
        if ($db) {
            echo "Connexion réussi";
        } else {
            echo "Échec de la connexion à la base de données. Veuillez vérifier vos paramètres.";
        }
    }

}
