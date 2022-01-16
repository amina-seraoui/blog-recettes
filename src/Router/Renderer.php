<?php

namespace App\Router;

class Renderer
{
    private string $layout = 'layout';

    public function __construct(private \PDO $pdo) {}

    public function render(string $path, array $params = [])
    {
        return $this->loadLayout($path, $params);
    }

    private function loadView(string $path, array $params): string
    {
        ob_start();
            extract($params);
            require $path;
        return ob_get_clean();
    }

    private function loadLayout(string $path, array $params): string
    {
        $req = $this->pdo->query('SELECT name, slug FROM categories', \PDO::FETCH_OBJ);
        $categories = $req->fetchAll();
        $content = $this->loadView($path, $params);

        ob_start(); 
            require dirname(__DIR__) . '/views/' . $this->layout . '.php';
        return ob_get_clean();
    }
}