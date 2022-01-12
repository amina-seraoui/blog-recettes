<?php

namespace App\Controller;

use PDO;

class Category extends Controller {
    private string $slug;
    private ?object $category = null;
    private int $limit = 9;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
        parent::__construct();
    }

    public function __invoke(): string
    {
        define('LEVELS', [
            1 => 'facile',
            2 => 'amateur',
            3 => 'experimenté'
        ]);

        $req = $this->pdo->prepare(
            'SELECT name, slug, id FROM categories WHERE slug = :slug'
        );
        $req->bindParam('slug', $this->slug, \PDO::PARAM_STR);
        $req->execute();

        if ($req->rowCount() < 1) {
            $this->pageNotFound();
        } else {
            $this->category = $req->fetch(\PDO::FETCH_OBJ);
        }

        [$current, $last] = $this->getPage();
        return $this->show($current, $this->getPagination($current, $last));        
    }

    /***
     * @return int[] [$currentPage, $lastPage]
     */
    private function getPage(): array
    {
        if (isset($_GET['p'])) {
            $p = (int)$_GET['p'];
            if ($p === 1) {
                $this->redirect('/' . $this->category->slug);
            } else if ($p === 0) {
                $this->pageNotFound();
            }
        } else {
            $p = 1;
        }

        $req = $this->pdo->prepare('SELECT COUNT(id) FROM recipes WHERE category_id = :id');
        $req->bindParam('id', $this->category->id, \PDO::PARAM_INT);
        $req->execute();
        $count = $req->fetch(\PDO::FETCH_COLUMN);

        $lastPage = (int)ceil($count / $this->limit);
        $lastPage = $lastPage > 0 ? $lastPage : 1;

        if ($p > $lastPage) {
            $this->redirect('/' . $this->category->slug . '?p=' . $lastPage);
        }

        return [$p, $lastPage];
    }

    private function getPagination($currentPage, $lastPage): string
    {
        $html = '<div class="pagination">';
        if ($lastPage < 5) {
            for ($i = 1; $i <= $lastPage; $i++) {
                $html .= '<a href="/' . $this->category->slug . '?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                $html .= '<a href="/' . $this->category->slug . '?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
            $html .= '<span>...</span>';
            for ($i = $lastPage - 1; $i <= $lastPage; $i++) {
                $html .= '<a href="/' . $this->category->slug . '?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
        }
        
        return $html .= '</div>';
    }

    private function show(int $page, string $pagination)
    {
        $offset = ($page - 1) * $this->limit;

        $req = $this->pdo->prepare( 
            'SELECT r.name, r.description, r.category_id, r.image, r.level, CONCAT(c.slug, \'/\', r.slug) AS r_slug
            FROM recipes AS r
            INNER JOIN categories AS c ON r.category_id = c.id
            WHERE r.category_id = :id
            ORDER BY r.id DESC
            LIMIT :limit
            OFFSET :offset'
        );
        $req->bindParam('id', $this->category->id, \PDO::PARAM_INT);
        $req->bindParam('limit', $this->limit, \PDO::PARAM_INT);
        $req->bindParam('offset', $offset, \PDO::PARAM_INT);
        $req->execute();

        $recipes = $req->fetchAll(\PDO::FETCH_OBJ);
        $category = $this->category;
        return $this->render('category', compact('recipes', 'category', 'pagination'));
    }
}