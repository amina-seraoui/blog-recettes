<?php

namespace App\Controller;

use PDO;

class Recipe extends Controller {
    private string $slug;
    private string $c_slug;
    private ?object $recipe = null;
    private int $limit = 9;

    public function __construct(string $slug, string $c_slug)
    {
        $this->slug = $slug;
        $this->c_slug = $c_slug;
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
            'SELECT r.id, r.name, r.image, r.slug, c.slug AS c_slug, r.description, r.prep_time, r.cook_time, r.level, r.ingredients, r.preparation, r.category_id
            FROM recipes as r
            INNER JOIN categories AS c ON c.id = r.category_id
            WHERE r.slug = :slug'
        );
        $req->bindParam('slug', $this->slug, \PDO::PARAM_STR);
        $req->execute();

        if ($req->rowCount() < 1) {
            $this->pageNotFound();
        } else {
            $this->recipe = $req->fetch(\PDO::FETCH_OBJ);

            if ($this->recipe->c_slug !== $this->c_slug) {
                $this->redirect('/' . $this->recipe->c_slug . '/' . $this->recipe->slug);
            }
        }

        return $this->show();        
    }

    private function show()
    {
        $req = $this->pdo->query( 
            'SELECT r.name, r.description, r.image, r.level, c.name AS category, CONCAT(c.slug, \'/\', r.slug) AS r_slug, c.slug AS c_slug
            FROM recipes AS r
            INNER JOIN categories AS c ON r.category_id = c.id
            ORDER BY r.id DESC
            LIMIT 2'
        );

        $recipe = $this->recipe;
        $recipes = $req->fetchAll(\PDO::FETCH_OBJ);
        return $this->render('recipe', compact('recipe', 'recipes'));
    }
}