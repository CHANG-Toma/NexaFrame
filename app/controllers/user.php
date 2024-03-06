<?php

namespace App\Controllers;

use App\Models\User as UserModel;
use PHPMailer\PHPMailer\PHPMailer;


class User
{
    public function __construct()
    {
    }

    public function login(): void // à modifier
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user = new UserModel();
                $loggedInUser = $user->getOneBy(["email" => $email]);

                if ($loggedInUser) {
                    $user->populate($loggedInUser);

                    if (password_verify($password, $user->getPassword())) {
                        $_SESSION["user"] = $user;
                        header("Location: /dashboard");
                    } else {
                        $error = "Mot de passe incorrect";
                    }
                } else {
                    $error = "Aucun utilisateur trouvé avec cette adresse e-mail.";
                }
            } else {
                $error = "Adresse e-mail invalide";
            }
        }
        include __DIR__ . '/../Views/back-office/installer/installer_Login.php';
    }

    public function forgotPassword(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = $_POST["email"];

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $user = new UserModel();
                $loggedInUser = $user->getOneBy(["email" => $email]);

                if ($loggedInUser) {
                    $user->populate($loggedInUser);

                    $newPwd = bin2hex(random_bytes(16));
                    $NewRandomHashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);
                    $user->setPassword($NewRandomHashedPwd);
                    $user->save();

                    $mailConfig = include __DIR__ . "/../config/MailConfig.php";

                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = $mailConfig['host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $mailConfig['username'];
                    $mail->Password = $mailConfig['password'];
                    $mail->SMTPSecure = $mailConfig['encryption'];
                    $mail->Port = $mailConfig['port'];

                    $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
                    $mail->addAddress($email);
                    $mail->Subject = "Nouveau mot de passe pour votre compte Nexaframe";
                    $mail->isHTML(true);
                    $mail->Body = "Bonjour,
                <br>Un nouveau mot de passe a été généré pour votre compte.<br>
                <br>Votre nouveau mot de passe est : <strong>" . $newPwd . "</strong>
                <br>Vous pouvez le changer une fois connecté à votre compte.
                <br>
                <br>Vous pouvez vous connecter à votre compte en cliquant sur le lien suivant : <a href='http://localhost/installer/login'>Se connecter</a>
                <br><br>Cordialement,
                <br>L'équipe Nexaframe.";
                    $mail->send();

                    $success = "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
                } else {
                    $error = "Aucun utilisateur trouvé avec cette adresse e-mail.";
                }
            }
            else {
                $error = "Bien tenté :)";
            }
        }
        include __DIR__ . '/../Views/back-office/installer/installer_ForgotPwdAdmin.php';
    }

    public function logout(): void
    {
        session_start();
        unset($_SESSION["user"]);
        session_destroy();
        if($_SERVER['REQUEST_URI'] === '/dashboard/logout'){
            header("Location: /installer/login");
        }
        else {
            header("Location: /login");
        }
    }
}
