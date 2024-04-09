<?php

use App\Models\User;

// Instancier UserModel pour accéder à la base de données
$userModel = new User();

// On supprime les utilisateurs qui ont été 'supprimés' il y a plus de 30 jours
$userModel->softDelete();