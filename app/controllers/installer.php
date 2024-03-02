<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\users;
use App\Models\Settings;
use App\Models\pages;

include __DIR__ . '/../core/DB.php';

class installer
{
    public function __construct()
    {
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/front-office/main/installer_configBDD.php';
    }

    public function configDatabase(): void
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
        $db = new DB();
        // Teste la connexion
        if ($db->testConnection()) {
            if ($this->migrateDatabase($db)) {
                $message = "Database is connected and migrate successfully.";
                include __DIR__ . '/../Views/front-office/main/installer_configAdmin.php';
            }
        } else {
            header('Location: /installer/process');
        }
    }

    // Configuration de la BDD pour l'installeur

    public function getDsnFromDbType(string $db_type): string //pour la connexion
    {
        $dsn = null;
        switch ($db_type) {
            case "sqlsrv":
                $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME;
                break;
            case "mysql":
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                break;
            case "pgsql":
                $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT;
                break;
            case "oci":
                $dsn = "oci:dbname=//" . DB_HOST . ":" . DB_PORT . "/" . DB_NAME;
                break;
        }
        return $dsn;
    }

    public function migrateDatabase($db): bool
    {
        $sqlScript = file_get_contents(__DIR__ . '/../db/script.sql');
        $queries = explode(';', $sqlScript);

        foreach ($queries as $query) {
            if (trim($query) != '') {
                if (!$db->exec($query)) {
                    echo "Database migration failed.";
                    return false;
                }
            }
        }
        return true;
    }

    // Gestion de l'administrateur pour l'installeur
    public function createAdmin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (
                isset($_REQUEST["login"]) &&
                isset($_REQUEST["email"]) &&
                isset($_REQUEST["password"]) &&
                isset($_REQUEST["password_confirm"])
            ) {
                print_r($_REQUEST);
                if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
                    if ($_REQUEST["password"] == $_REQUEST["password_confirm"]) {
                        $admin = new users();
                        $admin->setLogin($_REQUEST['login']);
                        $admin->setEmail($_REQUEST['email']);
                        $admin->setPassword(password_hash($_REQUEST['password'], PASSWORD_DEFAULT));
                        $admin->setRole('admin');
                        $admin->save();
                    }
                    else {
                        $message = "Les mots de passe ne correspondent pas";
                    }
                } else {
                    $message = "L'adresse email n'est pas valide";
                }
            } else {
                $message = "Erreur lors de la création de l'administrateur";
            }
            include __DIR__ . '/../Views/front-office/main/installer_configAdmin.php';
        }
        else {
            header('Location: /installer/processForm');
        }
    }

    private function InsertDefaultData(): void
    {
        $page = new Pages();
        $page->setUrl("/");
        $page->setTitle("Accueil");
        $page->setContent('<div class="container editable sortable-element" data-sortable="true" style="height: 100vh;" draggable="false"><div class="container editable sortable-element align-horizontal-start align-vertical-center" data-sortable="true" style="padding-right: 0px;" draggable="false"><div class="row editable sortable-element" data-sortable="true" style="display: flex; justify-content: space-between; align-items: center; padding-left: 0px; padding-right: 0px;" draggable="false"><div class="col-sm-8 editable sortable-element" data-sortable="true" draggable="false" style="width: 50%;"><p class="editable editable-text" draggable="false" contenteditable="false" style="text-transform: uppercase; font-size: 14px; color: rgb(110, 112, 118); font-weight: 500; letter-spacing: 1.5px;">centre de ressource</p><div class="editable editable-text" draggable="false" contenteditable="false" style="font-size: 64px; font-weight: 800; color: rgb(38, 38, 58); text-transform: none;">WispBlog - CMF,&nbsp;<div>développement web et outils technologiques</div></div></div><div class="col-sm-4 editable sortable-element" data-sortable="true" draggable="false" style="width: 50%;"><div class="editable image-container" draggable="false" style="max-height: 100%; margin-left: auto; margin-right: 0px;"><img src="https://cdn.dribbble.com/users/3873964/screenshots/14092691/media/d825b72de91e141ce5a66875adbe006d.gif" alt="myimage" draggable="false"></div></div></div></div></div><div class="container editable sortable-element" data-sortable="true" draggable="false" style="padding: 0px;"></div><div class="container editable sortable-element" data-sortable="true" draggable="false" style="padding: 0px;"></div>');
        $page->setMetaDescription("Meta description");
        $page->setIdCreator(1);
        $page->save();
    }

    private function InsertDefaultSettings(): void
    {
        $settingsCssPrimary = new Settings();
        $settingsCssPrimary->setKey("css:primary");
        $settingsCssPrimary->setValue("#1c1c2b");
        $settingsCssPrimary->save();

        $settingsCssSecondary = new Settings();
        $settingsCssSecondary->setKey("css:secondary");
        $settingsCssSecondary->setValue("#26263a");
        $settingsCssSecondary->save();

        $settingsCssTercery = new Settings();
        $settingsCssTercery->setKey("css:tercery");
        $settingsCssTercery->setValue("#7e37d8");
        $settingsCssTercery->save();

        $settingsCssMainFont1 = new Settings();
        $settingsCssMainFont1->setKey("css:main-font1");
        $settingsCssMainFont1->setValue("Plus Jakarta Sans");
        $settingsCssMainFont1->save();

        $settingsCssMainRadius = new Settings();
        $settingsCssMainRadius->setKey("css:main-radius");
        $settingsCssMainRadius->setValue("6px");
        $settingsCssMainRadius->save();

        $settingsCssTransitionDuration = new Settings();
        $settingsCssTransitionDuration->setKey("css:transition-duration");
        $settingsCssTransitionDuration->setValue("0.3s");
        $settingsCssTransitionDuration->save();
    }
}
