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
                $_SESSION['error_message'] = "Veuillez remplir tous les champs";
                header('Location: /dashboard/user');
            }

            if (password_verify($currentPassword, $_SESSION['user']['password'])) {
                if ($newPassword === $confirmPassword && strlen($newPassword) >= 8) {
                    $user = new UserModel();
                    $userdata = $user->getOneBy(["email" => $_SESSION['user']['email']]);
                    if(empty($userdata)) {
                        $_SESSION['error_message'] = "Utilisateur introuvable";
                        header('Location: /dashboard/user');
                    } else {
                        $user->populate($userdata);
                        $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
                        $user->save();
                        
                        $_SESSION['success_message'] = "Mot de passe modifié avec succès";                
                    }              
                } else {
                    $_SESSION['error_message'] = "Les mots de passe ne correspondent pas ou sont inférieurs à 8 caractères";
                }
            }
            else {
                $_SESSION['error_message'] = "Mot de passe actuel incorrect";
            }
            header('Location: /dashboard/user');
        }
    }
}
