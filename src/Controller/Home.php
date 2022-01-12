<?php

namespace App\Controller;

class Home extends Controller {
    public function __invoke(): string
    {
        define('LEVELS', [
            1 => 'facile',
            2 => 'amateur',
            3 => 'experimenté'
        ]);

        $recipes = $this->pdo->query(
            'SELECT r.name, r.description, r.image, r.level, c.name AS category, CONCAT(c.slug, \'/\', r.slug) AS r_slug, c.slug AS c_slug
            FROM recipes AS r
            INNER JOIN categories AS c ON c.id = r.category_id
            ORDER BY r.id DESC
            LIMIT 10',
            \PDO::FETCH_OBJ
        );

        $first = $recipes->fetch();

        return $this->render('home', compact('recipes', 'first'));
    }
}