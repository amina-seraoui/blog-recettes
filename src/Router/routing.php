<?php

use App\Controller\Admin\Login;
use App\Controller\Admin\Logout;
use App\Controller\Category;
use App\Controller\Home;
use App\Controller\Recipe;
use App\Controller\Admin\Recipe as AdminRecipe;
use App\Controller\Api\Stars;
use App\Router\PageNotFoundException;

// To move on appropriate file :
define('LEVELS', [
    1 => 'facile',
    2 => 'amateur',
    3 => 'expérimenté'
]);

$router = new App\Router\Router($_SERVER['REQUEST_URI']);

$router
    ->get('/', Home::class, 'home')
    ->get('/connexion', Login::class, 'login')
    ->post('/connexion', Login::class)
    ->get('/deconnexion', Logout::class)
    ->get('/admin/recipes', AdminRecipe::class, 'admin.recipe.index')
    ->get('/admin/recipes/new', AdminRecipe::class, 'admin.recipe.create')
    ->post('/admin/recipes/new', AdminRecipe::class)
    ->get('/admin/recipes/[id:i]', AdminRecipe::class, 'admin.recipe.update')
    ->post('/admin/recipes/[id:i]', AdminRecipe::class, 'admin.recipe.update')
    ->post('/admin/recipes/delete/[id:i]', AdminRecipe::class, 'admin.recipe.update')
    ->post('/api/stars', Stars::class, 'api.stars')
    ->get('/[c_slug:slug]', Category::class, 'category.show')
    ->get('/[c_slug:slug]/[r_slug:slug]', Recipe::class, 'recipe.show')
;

try {
    $response = $router->match();
    
    if (is_string($response)) {
        echo $response;
    }
} catch (Exception $e) {
    echo "Error  {$e->getCode()} : {$e->getMessage()}";
    die();
}
