<?php

namespace App\Controller;

class Category extends Controller {
    private ?object $category = null;
    private int $limit = 9;
    private string $slug;

    public function __invoke(string $slug): string
    {
        $this->slug = $slug;

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
        return $this->show($current, $this->getPagination($current, $last, '/' . $this->category->slug));        
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

        return [(int)$p, $lastPage];
    }

    private function show(int $page, string $pagination)
    {
        $offset = ($page - 1) * $this->limit;

        $req = $this->pdo->prepare( 
            'SELECT CEIL(AVG(s.note)) note, r.id, r.name, r.description, r.category_id, r.image, r.level, CONCAT(c.slug, \'/\', r.slug) AS r_slug
            FROM recipes AS r
            INNER JOIN categories AS c ON r.category_id = c.id
            LEFT JOIN stars s ON s.recipe_id = r.id
            WHERE r.category_id = :id
            GROUP BY r.id
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
