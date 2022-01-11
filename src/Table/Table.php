<?php

namespace App\Table;

class Table
{
    private ?\PDO $pdo = null;

    public function getPDO()
    {
        if (is_null($this->pdo)) {
            try {
                $this->pdo = new \PDO('mysql:dbname=blog_recipes;host:127.0.0.1;port:3306;charset:utf-8', 'root');
            } catch(\Exception $e) {
                echo 'PDO Erreur : ' . $e->getMessage();
                // phpinfo();
                die();
            }
        }
        return $this->pdo;
    }
}
