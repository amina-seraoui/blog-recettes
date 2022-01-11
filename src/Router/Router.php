<?php

namespace App\Router;

use App\Controller\Home;

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/') {
    echo (new Home())();
}

elseif ($uri === '/login') {
    require 'views/login.php';
}

elseif (preg_match('#^/([a-z\-0-9]{3,})$#i', $uri, $matches)) {
    $category = $matches[1];
    require 'views/category.php';
}

elseif (preg_match('#^/([a-z\-0-9]{3,})/([a-z\-0-9]{3,})$#i', $uri, $matches)) {
    $category = $matches[1];
    $recipe = $matches[2];
    require 'views/recipe.php';
}