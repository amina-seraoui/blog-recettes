<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

class Logout extends Controller
{
    public function __invoke(): string
    {
        unset($_SESSION['user']);
        return $this->redirect('connexion');
    }
}