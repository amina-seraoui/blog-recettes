<?php

namespace App\Controller;

use App\Router\PageNotFoundException;
use App\Router\Renderer;
use App\Table\Table;

abstract class Controller {
    private Renderer $renderer;
    protected \PDO $pdo;

    public function __construct()
    {
        $table = new Table();
        $pdo = $table->getPDO();
        $this->pdo = $pdo;
        $this->renderer = new Renderer($pdo);
    }

    protected function render(string $view, array $params = [])
    {
        return $this->renderer->render(dirname(__DIR__) . '/views' . '/' . $view . '.php', $params);
    }
    
    protected function pageNotFound()
    {
        throw new PageNotFoundException();
        // echo 'Erreur 404';
        // http_response_code(404);
        // // header('Location: 404');
        // die();
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url, true, 301);
        die();
    }
}

