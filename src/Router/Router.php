<?php

namespace App\Router;

use App\Controller\Category;
use App\Controller\Home;
use App\Controller\Recipe;

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/') {
    echo (new Home())();
}

elseif ($uri === '/login') {
    require 'views/login.php';
}

elseif (preg_match('#^\/([a-z\-0-9]{3,})(\?=.*)?$#', $uri, $matches)) {
    echo (new Category($matches[1]))();
}

elseif (preg_match('#^\/([a-z\-0-9]{3,})/([a-z\-0-9]{3,})(\?=.*)?$#', $uri, $matches)) {
    echo (new Recipe($matches[2], $matches[1]))();
    
    // $category = $matches[1];
    // $recipe = $matches[2];
    // require 'views/recipe.php';
}

else {
    echo 'Erreur 404';
    http_response_code(404);
}