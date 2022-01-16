<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

class Login extends Controller
{
    public function __invoke(): string
    {
        if (isset($_SESSION['user'])) {
            return $this->redirect('admin/recipes');
        }

        $error = '';
        $item = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req = $this->pdo->prepare('SELECT id, password FROM users WHERE username = :username');
            $req->bindParam('username', $_POST['username'], \PDO::PARAM_STR);
            $req->execute();

            if ($req->rowCount() > 0) {
                $user = $req->fetch(\PDO::FETCH_OBJ);

                if (password_verify($_POST['password'], $user->password)) {
                    $_SESSION['user'] = $user->id;
                    return $this->redirect('/admin/recipes');
                }
            }
            $error = 'Identifiants incorrects';
            $item = $_POST;
        }
        return $this->render('admin\login', compact('item', 'error'));
    }
}