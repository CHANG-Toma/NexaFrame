<?php

namespace App\Controllers;

use App\Models\User as UserModel;
use PHPMailer\PHPMailer\PHPMailer;


Class User
{
    public function __construct()
    {
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