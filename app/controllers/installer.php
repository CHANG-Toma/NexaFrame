<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\Page;
use App\Models\User;
use App\Models\Setting;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Installer
{
    public function __construct()
    {
    }

    public function index(): void
    {
        include __DIR__ . '/../Views/back-office/installer/installer_configBDD.php';
    }

    public function configDatabase(): void
    {
        //sécurité à faire ici pour éviter les injections SQL (htmlspecialchars, strip_tags, etc.)
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

        $db = DB::getInstance();
        // Teste la connexion

        if (!$db->testConnection()) {
            $error = "Database connection failed.";
            include __DIR__ . '/../Views/back-office/installer/installer_configBDD.php';
        } else {
            $user = new User();
            if (!empty($user->getOneBy(["role" => "admin"]))) {
                include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
            } else {
                if ($this->migrateDatabase($db)) {
                    include __DIR__ . '/../Views/back-office/installer/installer_registerAdmin.php';
                } else {
                    $error = "Database migration failed.";
                    include __DIR__ . '/../Views/back-office/installer/installer_configBDD.php';
                }
            }

        }
    }

    // Gestion de l'administrateur pour l'installeur
    public function createAdmin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST["login"] != "" && $_POST["email"] != "" && $_POST["password"] != "" && $_POST["password_confirm"] != "") {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $email = $_POST['email'];
                    if ($_POST["password"] == $_POST["password_confirm"]) {

                        $adminAcc = new User();
                        $adminAcc->setLogin($_POST['login']);
                        $adminAcc->setEmail($email);
                        $adminAcc->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
                        $adminAcc->setRole('admin');

                        $adminAcc->setValidation_token(md5(uniqid()));

                        $adminAcc->save();

                        if (!empty($adminAcc->getOneBy(["role" => "admin"]))) {
                            try {

                                $mailConfig = include __DIR__ . "/../config/MailConfig.php";
                                $mail = new PHPMailer(true);

                                $mail->isSMTP();
                                $mail->Host = $mailConfig['host'];
                                $mail->SMTPAuth = true;
                                $mail->Username = $mailConfig['username'];
                                $mail->Password = $mailConfig['password'];
                                $mail->SMTPSecure = $mailConfig['encryption'];
                                $mail->Port = $mailConfig['port'];

                                // Sender and recipient settings
                                $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
                                $mail->addAddress($email);

                                // Email content
                                $mail->isHTML(true);
                                $mail->Subject = 'Account Confirmation';
                                $mail->Body = 'Veuillez cliquer sur le lien suivant pour confirmer votre compte : 
                                <a href="http://localhost/installer/validate?token=' . $adminAcc->getValidation_token() . '">
                                Confirmer le compte</a>';

                                $mail->send();

                            } catch (Exception $e) {
                                $message = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                            }
                        } else {
                            $message = "Erreur lors de la création de l'administrateur";
                        }
                        include __DIR__ . "/../Views/back-office/installer/installer_loginAdmin.php";
                    } else {
                        $message = "Les mots de passe ne correspondent pas";
                    }
                } else {
                    $message = "L'adresse email n'est pas valide";
                }
            } else {
                $message = "Tous les champs sont obligatoires";
            }
        } else {
            header('Location: /installer/processForm');
            exit();
        }
    }

    public function validateAdmin(): void
    {
        $user = new User();
        $token = $_GET['token'];
        $userData = $user->getOneBy(["validation_token" => $token]);

        if ($userData !== false) {
            $user->populate($userData);
            if ($user->getId() > 0) {
                $user->setValidate(true);
                $user->setValidation_token(null); //on vide le token pour ne pas le réutiliser pour une autre validation
                $user->save();
                $success = "Votre compte a été validé avec succès.";
            } else {
                $error = 'Token invalide ou expiré. Veuillez réessayer.';
            }
        } else {
            $error = 'Token invalide ou expiré. Veuillez réessayer.';
        }

        include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
    }

    public function getDsnFromDbType(string $db_type): string //pour la connexion
    {
        $dsn = "";
        switch ($db_type) {
            case "mysql":
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                break;
            case "pgsql":
                $dsn = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT;
                break;
            case "oci":
                $dsn = "oci:dbname=//" . DB_HOST . ":" . DB_PORT . "/" . DB_NAME;
                break;
            case "sqlsrv":
                $dsn = "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME;
                break;
        }
        return $dsn;
    }

    public function migrateDatabase($db): bool
    {
        $sqlScript = file_get_contents(__DIR__ . '/../db/script.sql');
        $queries = array_filter(explode(';', $sqlScript), 'trim');

        foreach ($queries as $query) {
            $result = $db->exec($query);
            if ($result === false) {
                echo "Database migration failed.";
                return false;
            }
        }
        return true;
    }

    private function InsertDefaultData(): void
    {
        $page = new Page();
        $page->setUrl("/");
        $page->setTitle("Accueil");
        $page->setContent('<div class="container editable sortable-element" data-sortable="true" style="height: 100vh;" draggable="false"><div class="container editable sortable-element align-horizontal-start align-vertical-center" data-sortable="true" style="padding-right: 0px;" draggable="false"><div class="row editable sortable-element" data-sortable="true" style="display: flex; justify-content: space-between; align-items: center; padding-left: 0px; padding-right: 0px;" draggable="false"><div class="col-sm-8 editable sortable-element" data-sortable="true" draggable="false" style="width: 50%;"><p class="editable editable-text" draggable="false" contenteditable="false" style="text-transform: uppercase; font-size: 14px; color: rgb(110, 112, 118); font-weight: 500; letter-spacing: 1.5px;">centre de ressource</p><div class="editable editable-text" draggable="false" contenteditable="false" style="font-size: 64px; font-weight: 800; color: rgb(38, 38, 58); text-transform: none;">Nexaframe - CMF,&nbsp;<div>développement web et outils technologiques</div></div></div><div class="col-sm-4 editable sortable-element" data-sortable="true" draggable="false" style="width: 50%;"><div class="editable image-container" draggable="false" style="max-height: 100%; margin-left: auto; margin-right: 0px;"><img src="https://cdn.dribbble.com/users/3873964/screenshots/14092691/media/d825b72de91e141ce5a66875adbe006d.gif" alt="myimage" draggable="false"></div></div></div></div></div><div class="container editable sortable-element" data-sortable="true" draggable="false" style="padding: 0px;"></div><div class="container editable sortable-element" data-sortable="true" draggable="false" style="padding: 0px;"></div>');
        $page->setMetaDescription("Meta description");
        $page->setIdCreator(1);
        $page->save();
    }
    private function InsertDefaultSettings(): void
    {
        $settingsCssPrimary = new Setting();
        $settingsCssPrimary->setKey("css:primary");
        $settingsCssPrimary->setValue("#1c1c2b");
        $settingsCssPrimary->save();

        $settingsCssSecondary = new Setting();
        $settingsCssSecondary->setKey("css:secondary");
        $settingsCssSecondary->setValue("#26263a");
        $settingsCssSecondary->save();

        $settingsCssTercery = new Setting();
        $settingsCssTercery->setKey("css:tercery");
        $settingsCssTercery->setValue("#7e37d8");
        $settingsCssTercery->save();

        $settingsCssMainFont1 = new Setting();
        $settingsCssMainFont1->setKey("css:main-font1");
        $settingsCssMainFont1->setValue("Plus Jakarta Sans");
        $settingsCssMainFont1->save();

        $settingsCssMainRadius = new Setting();
        $settingsCssMainRadius->setKey("css:main-radius");
        $settingsCssMainRadius->setValue("6px");
        $settingsCssMainRadius->save();

        $settingsCssTransitionDuration = new Setting();
        $settingsCssTransitionDuration->setKey("css:transition-duration");
        $settingsCssTransitionDuration->setValue("0.3s");
        $settingsCssTransitionDuration->save();
    }

    public function loginAdmin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if (empty($login) || empty($password)) {
                $error = "Nom d'utilisateur ou mot de passe incorrect";
                include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
            }

            $user = new User();
            $loggedInUser = $user->getOneBy(["login" => $login]);
            $user->populate($loggedInUser);

            if ($loggedInUser && password_verify($password, $user->getPassword())) {
                if($user->getRole() == "admin" && $user->isValidate() == true) {
                    $_SESSION['user'] = $user;
                    header('Location: /dashboard');
                } else {
                    $error = "Compte non validé, veuillez vérifier votre boîte mail pour valider votre compte.";
                    include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
                }    
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect";
                include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
            }
        } else {
            include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
        }
    }
}