<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use PDO;

class Recipe extends Controller {
    private int $limit = 9;

    public function __invoke(?int $id = null): string
    {
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/connexion');
        }

        if (str_ends_with($_SERVER['REQUEST_URI'], '/new')) {
            return $this->create($_SERVER['REQUEST_METHOD']);
        }

        if (!is_null($id)) {
            if (str_ends_with($_SERVER['REQUEST_URI'], '/delete/' . $id)) {
                return $this->delete($id);
            }
            return $this->update($id, $_SERVER['REQUEST_METHOD']);
        }
        return $this->index();
    }

    private function index(): string
    {
        [$current, $last] = $this->getPage();
        $offset = ($current - 1) * $this->limit;

        $req = $this->pdo->prepare( 
            'SELECT r.id, c.id AS c_id, r.name, r.image, c.name AS category, CONCAT(c.slug, \'/\', r.slug) AS slug
            FROM recipes AS r
            INNER JOIN categories AS c ON r.category_id = c.id
            ORDER BY r.id DESC
            LIMIT :limit
            OFFSET :offset'
        );
        $req->bindParam('limit', $this->limit, \PDO::PARAM_INT);
        $req->bindParam('offset', $offset, \PDO::PARAM_INT);
        $req->execute();

        $recipes = $req->fetchAll(\PDO::FETCH_OBJ);
        $pagination = $this->getPagination($current, $last);
        return $this->render('admin/recipe/index', compact('recipes', 'pagination'));
    }

    private function create(string $method): string
    {
        $item = [];
        if ($method === 'POST') {
            if (isset($_POST['name'], $_POST['slug'], $_POST['description'], $_POST['level'], $_FILES['image'], $_POST['prep_time'], $_POST['cook_time'], $_POST['ingredients'], $_POST['preparation'], $_POST['category_id'])) {
                $slug = (!empty($_POST['slug'])) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $_POST['name']));

                $ext = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                $image = uniqid('recipe-') . '.' . $ext;

                $req = $this->pdo->prepare(
                    'INSERT INTO recipes
                    (name, slug, description, level, image, prep_time, cook_time, ingredients, preparation, category_id, author_id)
                    VALUES (:name, :slug, :description, :level, :image, :prep_time, :cook_time, :ingredients, :preparation, :category_id, :author_id)'
                );
                $req->bindParam('name', $_POST['name'], \PDO::PARAM_STR);
                $req->bindParam('slug', $slug, \PDO::PARAM_STR);
                $req->bindParam('description', $_POST['description'], \PDO::PARAM_STR);
                $req->bindParam('level', $_POST['level'], \PDO::PARAM_INT);
                $req->bindParam('image', $image, \PDO::PARAM_STR);
                $req->bindParam('prep_time', $_POST['prep_time'], \PDO::PARAM_INT);
                $req->bindParam('cook_time', $_POST['cook_time'], \PDO::PARAM_INT);
                $req->bindParam('ingredients', $_POST['ingredients'], \PDO::PARAM_STR);
                $req->bindParam('preparation', $_POST['preparation'], \PDO::PARAM_STR);
                $req->bindParam('category_id', $_POST['category_id'], \PDO::PARAM_INT);
                $req->bindParam('author_id', $_SESSION['user'], \PDO::PARAM_INT);
                $success = $req->execute();

                if ($success) {
                    $this->createImage($_FILES['image'], $image);
        
                    header('Location: /admin/recipes');
                } else {
                    $item = [...$_POST, $_FILES];
                }
            }
        }

        $categories = $this->pdo->query('SELECT id, name FROM categories', \PDO::FETCH_KEY_PAIR);
        $categories = $categories->fetchAll();

        return $this->render('admin/recipe/create', compact('categories', 'item'));
    }

    private function update(int $id, string $method): string
    {
        $req = $this->pdo->prepare(
            'SELECT id, name, slug, description, level, image, prep_time, cook_time, ingredients, preparation, category_id, author_id
            FROM recipes
            WHERE id = :id'
        );
        $req->bindParam('id', $id, \PDO::PARAM_INT);
        $req->execute();
        $item = $req->fetch(\PDO::FETCH_ASSOC);

        if ($method === 'POST') {
            if (isset($_POST['name'], $_POST['slug'], $_POST['description'], $_POST['level'], $_FILES['image'], $_POST['prep_time'], $_POST['cook_time'], $_POST['ingredients'], $_POST['preparation'], $_POST['category_id'])) {
                $slug = (!empty($_POST['slug'])) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $_POST['name']));

                $ext = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                $image = uniqid('recipe-') . '.' . $ext;

                $req = $this->pdo->prepare(
                    'UPDATE recipes SET name = :name, slug = :slug, description = :description, level = :level, image = :image, prep_time = :prep_time, cook_time = :cook_time, ingredients = :ingredients, preparation = :preparation, category_id = :category_id, author_id = :author_id WHERE id = :id'
                );
                $req->bindParam('name', $_POST['name'], \PDO::PARAM_STR);
                $req->bindParam('slug', $slug, \PDO::PARAM_STR);
                $req->bindParam('description', $_POST['description'], \PDO::PARAM_STR);
                $req->bindParam('level', $_POST['level'], \PDO::PARAM_INT);
                $req->bindParam('image', $image, \PDO::PARAM_STR);
                $req->bindParam('prep_time', $_POST['prep_time'], \PDO::PARAM_INT);
                $req->bindParam('cook_time', $_POST['cook_time'], \PDO::PARAM_INT);
                $req->bindParam('ingredients', $_POST['ingredients'], \PDO::PARAM_STR);
                $req->bindParam('preparation', $_POST['preparation'], \PDO::PARAM_STR);
                $req->bindParam('category_id', $_POST['category_id'], \PDO::PARAM_INT);
                $req->bindParam('id', $id, \PDO::PARAM_INT);
                $req->bindParam('author_id', $_SESSION['user'], \PDO::PARAM_INT);
                $success = $req->execute();

                if ($success) {
                    $s = $this->removeImage($item['image']);
                    $this->createImage($_FILES['image'], $image);
        
                    header('Location: /admin/recipes?success=' . (string)$s);
                } else {
                    $item = [...$_POST, $_FILES];
                }
            }
        }

        $categories = $this->pdo->query('SELECT id, name FROM categories', \PDO::FETCH_KEY_PAIR);
        $categories = $categories->fetchAll();

        return $this->render('admin/recipe/update', compact('item', 'categories'));
    }

    private function delete(int $id)
    {
        $req = $this->pdo->prepare('SELECT image FROM recipes WHERE id = :id');
        $req->bindParam('id', $id, \PDO::PARAM_INT);
        $req->execute();
        
        if ($req->rowCount() > 0) {
            $item = $req->fetch(\PDO::FETCH_ASSOC);
            $this->removeImage($item['image']);
            $req = $this->pdo->prepare('DELETE FROM recipes WHERE id = :id');
            $req->bindParam('id', $id, \PDO::PARAM_INT);
            $req->execute();

            return $this->redirect('/admin/recipes');
        }
    }

    private function createImage(array $file, $name): bool
    {
        $arrayType = ["jpg" => 'image/jpg', "jpeg" => 'image/jpeg'];
        if (in_array($file['type'], $arrayType)) {
            $success = move_uploaded_file($file['tmp_name'], './uploads/img/' . $name);
            if ($success) { //create thumb
                $src = './uploads/img/' . $name;
                [$width, $height] = getimagesize($src);

                $maxWidth = 500;
                $maxHeight = 500;

                $ratio = $width / $height;
                
                if ($maxWidth / $maxHeight > $ratio) {
                    $maxWidth = $maxHeight * $ratio;
                } else {
                    $maxHeight = $maxWidth * $ratio; 
                }

                $img = imagecreatefromjpeg($src);
                $thumb = imagecreatetruecolor($maxWidth, $maxHeight);

                imagecopyresized($thumb, $img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
                
                return imagejpeg($thumb, './uploads/img/thumbs/' . $name);
            }
        }
        return false;
    }

    private function removeImage(string $name): bool
    {
        $path = 'uploads/img/';
        $s1 = $s2 = false;
        if (file_exists($path . $name)) {
            $s1 = unlink($path . $name);
        }
        if (file_exists($path . 'thumbs/' . $name)) {
            $s2 = unlink($path . 'thumbs/' . $name);
        }

        return ($s1 && $s2);
    }

     /***
     * @return int[] [$currentPage, $lastPage]
     */
    private function getPage(): array
    {
        if (isset($_GET['p'])) {
            $p = (int)$_GET['p'];
            if ($p === 1) {
                $this->redirect('/admin/recipes');
            } else if ($p === 0) {
                $this->pageNotFound();
            }
        } else {
            $p = 1;
        }

        $req = $this->pdo->query('SELECT COUNT(id) FROM recipes');
        $count = $req->fetch(\PDO::FETCH_COLUMN);

        $lastPage = (int)ceil($count / $this->limit);
        $lastPage = $lastPage > 0 ? $lastPage : 1;

        if ($p > $lastPage) {
            $this->redirect('/admin/recipes?p=' . $lastPage);
        }

        return [$p, $lastPage];
    }

    private function getPagination($currentPage, $lastPage): string
    {
        $html = '<div class="pagination">';
        if ($lastPage < 5) {
            for ($i = 1; $i <= $lastPage; $i++) {
                $html .= '<a href="/admin/recipes?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                $html .= '<a href="/admin/recipes?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
            $html .= '<span>...</span>';
            for ($i = $lastPage - 1; $i <= $lastPage; $i++) {
                $html .= '<a href="/admin/recipes?p=' . $i .'"' . ($currentPage === $i ? 'class="active"' : '') . '>' . $i . '</a>';
            }
        }
        
        return $html .= '</div>';
    }
}
