<?php

namespace App\Controller;

use App\Router\PageNotFoundException;
use PDO;

class Recipe extends Controller {
    private string $slug;
    private string $c_slug;
    private ?object $recipe = null;

    public function __invoke(string $c_slug, string $slug): string
    {
        $this->slug = $slug;
        $this->c_slug = $c_slug;

        $req = $this->pdo->prepare(
            'SELECT CEIL(AVG(s.note)) note, r.id, r.name, r.image, r.slug, c.slug c_slug, r.description, r.prep_time, r.cook_time, r.level, r.ingredients, r.preparation, r.category_id
            FROM recipes r
            LEFT JOIN categories c ON c.id = r.category_id
            LEFT JOIN stars s ON s.recipe_id = r.id
            WHERE r.slug = :slug'
        );
        $req->bindParam('slug', $slug, \PDO::PARAM_STR);
        $req->execute();

        if ($req->rowCount() < 1) {
            throw new PageNotFoundException('Aucune recette ne correspond au slug ' . $slug);
        } else {
            $this->recipe = $req->fetch(\PDO::FETCH_OBJ);

            if ($this->recipe->c_slug !== $this->c_slug) {
                return $this->redirect('/' . $this->recipe->c_slug . '/' . $this->recipe->slug);
            }
        }

        return $this->show();        
    }

    private function show()
    {
        $req = $this->pdo->prepare( 
            'SELECT r.id, r.name, r.description, r.image, r.level, c.name AS category, CONCAT(c.slug, \'/\', r.slug) AS r_slug, c.slug AS c_slug
            FROM recipes AS r
            INNER JOIN categories AS c ON r.category_id = c.id
            WHERE NOT r.id = ?
            ORDER BY r.id DESC
            LIMIT 2'
        );
        $req->execute([$this->recipe->id]);

        $recipe = $this->recipe;
        $recipes = $req->fetchAll(\PDO::FETCH_OBJ);
        return $this->render('recipe', compact('recipe', 'recipes'));
    }
}