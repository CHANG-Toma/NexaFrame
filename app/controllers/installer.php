<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\Page;
use App\Models\User;
use App\Models\Setting;

use App\Controllers\Error;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Installer
{
    // Affiche la page de configuration de la base de données
    public function index(): void
    {
        if($_SERVER["REQUEST_URI"] == "/installer") {
            include __DIR__ . '/../Views/back-office/installer/installer_configBDD.php';
        }
    }

    // exécute la migration de la base de données
    public function configDatabase(): void
    {
        //sécurité à faire ici pour éviter les injections SQL (htmlspecialchars, strip_tags, etc.)
        $dbConfig = [
            'DB_NAME' => htmlspecialchars(strip_tags($_POST['db_name'])),
            'DB_USER' => htmlspecialchars(strip_tags($_POST['db_user'])),
            'DB_PASSWORD' => htmlspecialchars(strip_tags($_POST['db_password'])),
            'DB_HOST' => htmlspecialchars(strip_tags($_POST['db_host'])),
            'DB_PORT' => filter_input(INPUT_POST, 'db_port', FILTER_SANITIZE_NUMBER_INT),
            'DB_TYPE' => htmlspecialchars(strip_tags($_POST['db_type'])),
        ];

        $configContent = "<?php\n";
        foreach ($dbConfig as $key => $value) {
            $configContent .= "define('$key', '$value');\n";
        }

        // Crée le fichier de configuration
        file_put_contents('../app/config/Config.php', $configContent);

        $db = DB::getInstance();
        // Teste la connexion

        if (!$db->testConnection()) {
            $error = "Connexion échouée";
            header('Location: /installer');
        } else {
            $user = new User();
            if (!empty($user->getOneBy(["role" => "superadmin"]))) {
                header('Location: /installer/login');
            } else {
                if ($this->migrateDatabase($db)) {
                    header('Location: /installer/account');
                } else {
                    $error = "Database migration failed.";
                    include __DIR__ . '/../Views/back-office/installer/installer_configBDD.php';
                }
            }

        }
    }

    // Affiche la page d'inscription de l'administrateur
    public function createAdmin(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = trim(htmlspecialchars($_POST['login']));
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['password_confirm']);

            if ($login && $email && $password && $confirmPassword) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $user = new User();
                    $dataEmail = $user->getOneBy(["email" => $email]);
                    if(!empty($dataEmail)) {
                        $message = "L'adresse email est déjà utilisée";
                    }
                    else {
                        // Vérifie si les mots de passe correspondent et si le mot de passe contient au moins 8 caractères
                        if ($password == $confirmPassword) {
                            if (strlen($password) >= 8) {
                                $adminAcc = new User();
                                $adminAcc->setLogin($login);
                                $adminAcc->setEmail($email);
                                $adminAcc->setPassword(password_hash($password, PASSWORD_DEFAULT));
                                $adminAcc->setRole('superadmin');
                                $adminAcc->setValidation_token(md5(uniqid()));
    
                                $adminAcc->save();
    
                                // Envoi d'un email de confirmation de compte à l'administrateur
                                if (!empty($adminAcc->getOneBy(["role" => "superadmin"]))) {
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
                                header('Location: /installer/login');
                            } else {
                                $message = "Le mot de passe doit contenir au moins 8 caractères";
                            }
                        } else {
                            $message = "Les mots de passe ne correspondent pas";
                        }
                    }
                } else {
                    $message = "L'adresse email n'est pas valide";
                }
            } else {
                $message = "Tous les champs sont obligatoires";
            }
        }
        include __DIR__ . "/../Views/back-office/installer/installer_registerAdmin.php";
    }

    // Pour la connexion à la bdd
    public function getDsnFromDbType(string $db_type): string
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

    // Pour la migration de la base de données
    public function migrateDatabase($db): bool
    {
        // Récupère le script SQL
        $sqlScript = file_get_contents(__DIR__ . '/../db/script.sql');
        $queries = array_filter(explode(';', $sqlScript), 'trim');

        // Exécute les requêtes SQL une par une
        foreach ($queries as $query) {
            $result = $db->exec($query);
            
            if ($result === false) {
                return false;
            }
        }
        return true;
    }
}