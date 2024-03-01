<?php

namespace App\Controllers;

use App\Core\DB;
include __DIR__ . '/../core/DB.php';

class installer
{
    public function __construct()
    {
        //define('BASE_DIR', __DIR__ . '/..');
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/front-office/main/installer_config.php';
    }

    public function processForm(): void
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

        include '../app/config/config.php';
        // Teste la connexion
        if (new DB()) {
            echo "Connexion réussie";
            include __DIR__ . '/../Views/front-office/main/installer_success.php';
        } else {
            echo "Échec de la connexion à la base de données. Veuillez vérifier vos paramètres.";
        }
    }

    public function getDsnFromDbType(string $db_type): string
    {
        $dsn = null;
        switch ($db_type) {
            case "sqlsrv":
                $dsn = "sqlsrv:Server=".DB_HOST.";Database=".DB_NAME; break;
            case "mysql":
                $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME; break;
            case "pgsql":
                $dsn = "pgsql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT; break;
            case "oci":
                $dsn = "oci:dbname=//".DB_HOST.":".DB_PORT."/".DB_NAME; break;
        }
        return $dsn;
    }

}
