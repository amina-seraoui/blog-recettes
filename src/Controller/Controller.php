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
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url, true, 301);
        die();
    }

    protected function getPagination(int $currentPage, int $lastPage, string $url): string
    {
        $html = '<div class="pagination">';
        $html .= $this->linkPagination($url, $currentPage, 1); // => 1
        
        if ($lastPage < 6) { // => 1 2 3 4 5
            for ($i = 2; $i < $lastPage; $i++) {
                $html .= $this->linkPagination($url, $currentPage, $i);
            }
        } else {
            if ($currentPage > 3) { // => 1 ... 18 19 20 21
                $html .= '<span>...</span>';
            }
            
            if ($currentPage < 3) { // => 1 2 3
                $html .= $this->linkPagination($url, $currentPage, 2);
                $html .= $this->linkPagination($url, $currentPage, 3);
            } else if ($currentPage > $lastPage - 2) { // => 1 ... 19 20 21
                $html .= $this->linkPagination($url, $currentPage, $lastPage - 2);
                $html .= $this->linkPagination($url, $currentPage, $lastPage - 1);
            } else { // => 1 2 3 4 ... 6 ----- 1 ... 3 4 5 ... 21
                for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                    $html .= $this->linkPagination($url, $currentPage, $i);
                }
            }

            if ($currentPage < $lastPage - 2) { // => 1 ... 17 18 19 ... 21
                $html .= '<span>...</span>';
            }
        }
        
        if ($lastPage !== 1) {
            $html .= $this->linkPagination($url, $currentPage, $lastPage);
        }
        
        return $html .= '</div>';
    }

    private function linkPagination(string $url, int $currentPage, int $page): string
    {
        return '<a href="' . $url . '?p=' . $page .'"' . ($currentPage === $page ? 'class="active"' : '') . '>' . $page . '</a>';
    }
}

