<?php

namespace App\Controllers;

use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;

class Security
{

    // Inscription d'un utilisateur
    public function register(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupération des données du formulaire
            $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($login && $email && $password && $confirmPassword) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user = new User();
                    $dataUser = $user->getOneBy(["email" => $email]);
                    if (!empty($dataUser)) {
                        $message = "Un compte existe déjà avec cette adresse e-mail.";
                    } else {
                        // Vérifie si les mots de passe correspondent et si le mot de passe contient au moins 8 caractères
                        if ($password === $confirmPassword) {
                            if (strlen($password) >= 8) {
                                $user->setLogin($login);
                                $user->setEmail($email);
                                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
                                $user->setRole('user');
                                $user->setValidation_token(md5(uniqid()));

                                $user->save();
                                // Envoi d'un email de validation de compte à l'utilisateur
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

                                    $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
                                    $mail->addAddress($email);
                                    $mail->Subject = "Validation de votre compte Nexaframe";
                                    $mail->isHTML(true);
                                    $mail->Body = "Bonjour,
                                    <br>Merci de vous être inscrit sur Nexaframe. Veuillez cliquer sur le lien suivant pour valider votre compte : <a href='http://localhost/user/validate?token=" . $user->getValidation_token() . "'>Valider mon compte</a>";
                                    $mail->send();
                                } catch (\Exception $e) {
                                    $message = "Erreur lors de l'envoi du mail de validation. Veuillez réessayer.";
                                }
                            } else {
                                $message = "Les mots de passe ne correspondent pas ou sont inférieurs à 8 caractères.";
                            }
                        } else {
                            $message = "Les mots de passe ne correspondent pas ou sont inférieurs à 8 caractères.";
                        }
                    }
                } else {
                    $message = "Un compte existe déjà avec cette adresse e-mail.";
                }
                if (!empty($message)) {
                    header('Location: /register?message=' . $message);
                }
            } else {
                $message = "Champs vides. Veuillez remplir tous les champs.";
                header('Location: /register?message=' . $message);
            }
        }
        header('Location: /register');
    }

    // Connexion d'un utilisateur
    public function login(): void
    {
        // Vérifie si la session est démarrée
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty($login) || empty($password)) {
                $error = "Champs vides. Veuillez remplir tous les champs.";
            }

            // Récupère l'utilisateur en fonction de son login
            $user = new User();
            $loggedInUser = $user->getOneBy(["login" => $login]);

            // Vérifie si l'utilisateur existe et si le mot de passe est correct
            if (empty($loggedInUser)) {
                $error = "Nom d'utilisateur ou mot de passe incorrect";
            } else {
                if ($loggedInUser && password_verify($password, $loggedInUser[0]['password'])) {
                    $user->populate($loggedInUser);
                    // Vérifie si l'utilisateur est un administrateur ou un superadmin et si son compte est validé
                    if ($user->getRole() == "admin" || $user->getRole() == "superadmin" && $user->isValidate() == true) {
                        $_SESSION['user'] = $loggedInUser[0];
                        if ($_SERVER['REQUEST_URI'] === '/installer/login') {
                            header('Location: /dashboard/page-builder');
                        } else {
                            header('Location: /');
                        }
                        header('Location: /dashboard/page-builder');
                    } else if ($user->getRole() == "user" && $user->isValidate() == true) {
                        $_SESSION['user'] = $loggedInUser[0];
                        header('Location: /home');
                    }
                } else {
                    if ($_SERVER["REQUEST_URI"] == "/installer/login") {
                        header('Location: /installer/login');
                    } else {
                        header('Location: /login');
                    }
                }
            }
        }

        // Affiche la page de connexion en fonction de l'URL
        if ($_SERVER['REQUEST_URI'] === '/installer/login') {
            include __DIR__ . '/../Views/back-office/installer/installer_loginAdmin.php';
        } else if ($_SERVER['REQUEST_URI'] === '/user/login' && isset($_SESSION['user'])) {
            header('Location: /home');
        }
    }

    // Déconnexion d'un utilisateur
    public function logout(): void
    {
        session_start();
        // Détruit la session et redirige vers la page de connexion
        unset($_SESSION["user"]);
        session_destroy();
        if ($_SERVER['REQUEST_URI'] === '/dashboard/logout') {
            header("Location: /installer/login");
        } else {
            header("Location: /login");
        }
    }

    // Mot de passe oublié
    public function forgotPassword(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = $_POST["email"];

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Récupère l'utilisateur en fonction de son adresse e-mail
                $user = new User();
                $loggedInUser = $user->getOneBy(["email" => $email]);

                // Vérifie si l'utilisateur existe et envoie un e-mail avec un nouveau mot de passe
                if ($loggedInUser) {
                    $user->populate($loggedInUser);

                    // Génère un nouveau mot de passe et le sauvegarde dans la base de données
                    $newPwd = bin2hex(random_bytes(16));
                    $NewRandomHashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);
                    $user->setPassword($NewRandomHashedPwd);
                    $user->save();

                    //URL a changer avant de mettre en prod
                    $url = ($_SERVER['REQUEST_URI'] === '/installer/forgot-password') ? "http://localhost/installer/login" : "http://localhost/login";

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
                    $mail->Subject = "Nouveau mot de passe pour votre compte";
                    $mail->isHTML(true);
                    $mail->Body = "Bonjour,
                <br>Un nouveau mot de passe a été généré pour votre compte.<br>
                <br>Votre nouveau mot de passe est : <strong>" . $newPwd . "</strong>
                <br>Vous pouvez le changer une fois connecté à votre compte.
                <br>
                <br>Vous pouvez vous connecter à votre compte en cliquant sur le lien suivant : <a href='" . $url . "'>Se connecter</a>
                <br><br>Cordialement,
                <br>L'équipe Nexaframe.";
                    $mail->send();

                    $success = "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
                } else {
                    $error = "Aucun utilisateur trouvé avec cette adresse e-mail.";
                }
            } else {
                $error = "Bien tenté :)";
            }
        }
        if ($_SERVER['REQUEST_URI'] === '/installer/forgot-password') {
            include __DIR__ . '/../Views/back-office/installer/installer_ForgotPwdAdmin.php';
        } else {
            header('Location: /forgot-password');
        }
    }

    // Changement de mot de passe
    public function changePassword(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupération des données du formulaire
            $currentPassword = filter_input(INPUT_POST, "currentPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newPassword = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            session_start();

            // Vérifie si les champs sont vides
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error_message2'] = "Veuillez remplir tous les champs";
                if ($_SERVER['REQUEST_URI'] === '/dashboard/user') {
                    header('Location: /dashboard/user');
                } else {
                    header('Location: /user/reset-password');
                }
            }

            // Vérifie si le mot de passe actuel est correct
            if (password_verify($currentPassword, $_SESSION['user']['password'])) {
                if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
                    $user = new User();
                    $userdata = $user->getOneBy(["email" => $_SESSION['user']['email']]);
                    // Vérifie si l'utilisateur existe
                    if (empty($userdata)) {
                        $_SESSION['error_message2'] = "Utilisateur introuvable";
                        header('Location: /dashboard/user');
                    } else {
                        // Met à jour le mot de passe de l'utilisateur
                        $user->populate($userdata);
                        $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                        $user->setUpdated_at(date('Y-m-d H:i:s'));
                        $user->save();

                        $_SESSION['success_message2'] = "Mot de passe modifié avec succès";
                    }
                } else {
                    $_SESSION['error_message2'] = "Les mots de passe ne correspondent pas ou sont inférieurs à 8 caractères";
                }
            } else {
                $_SESSION['error_message2'] = "Mot de passe actuel incorrect";
            }
            if ($_SERVER['REQUEST_URI'] === '/dashboard/update-password') {
                header('Location: /dashboard/user');
            } else {
                header('Location: /user/login');
            }
        }
    }

    // Validation de compte
    public function validate(): void
    {
        $user = new User();
        $token = $_GET['token'];
        // Récupère l'utilisateur en fonction du token de validation
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
        // Affiche la page de validation en fonction de l'URL
        if ($_SERVER['REQUEST_URI'] === '/installer/validate?token=' . $token) {
            include __DIR__ . "/../Views/back-office/installer/installer_loginAdmin.php";
        } else if ($_SERVER['REQUEST_URI'] === '/user/validate?token=' . $token) {
            header('Location: /login');
        } else {
            include __DIR__ . "/../Views/front-office/main/home.php";
        }

    }

}
