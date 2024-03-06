<?php

namespace App\Controllers;

use App\Models\User as UserModel;
use PHPMailer\PHPMailer\PHPMailer;


class User
{
    public function __construct()
    {
    }

    public function changePassword(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $currentPassword = filter_input(INPUT_POST, "currentPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $newPassword = filter_input(INPUT_POST, "newPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            session_start();

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error_message2'] = "Veuillez remplir tous les champs";
                header('Location: /dashboard/user');
            }

            if (password_verify($currentPassword, $_SESSION['user']['password'])) {
                if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
                    $user = new UserModel();
                    $userdata = $user->getOneBy(["email" => $_SESSION['user']['email']]);
                    if (empty($userdata)) {
                        $_SESSION['error_message2'] = "Utilisateur introuvable";
                        header('Location: /dashboard/user');
                    } else {
                        $user->populate($userdata);
                        $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                        $user->save();

                        $_SESSION['success_message2'] = "Mot de passe modifié avec succès";
                    }
                } else {
                    $_SESSION['error_message2'] = "Les mots de passe ne correspondent pas ou sont inférieurs à 8 caractères";
                }
            } else {
                $_SESSION['error_message2'] = "Mot de passe actuel incorrect";
            }
            header('Location: /dashboard/user');
        }
    }

    public function showUser(): void
    {
        // Code to retrieve and display user information
    }

    public function editUser(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

            session_start();

            $user = new UserModel();
            $userdata = $user->getOneBy(["email" => $_SESSION['user']['email']]);
            if (empty($userdata)) {
                $_SESSION['error_message'] = "Utilisateur introuvable";
                header('Location: /dashboard/user');
            } else {
                $user->populate($userdata);
                if(!empty($login)){
                    $user->setLogin($login);
                }
                else{
                    $user->setEmail($email);
                }
                $user->save();
                $_SESSION['success_message'] = "Vos informations ont été mises à jour avec succès";
            }
            header('Location: /dashboard/user');
        }
    }

    public function deleteUser(): void
    {
        // Code to delete user
    }
    public function listUser(): void
    {
        // Code to list all users
    }
}