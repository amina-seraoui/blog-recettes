<?php

use App\Controller\Home;
use App\Router\Route;

require_once '../vendor/autoload.php';

//// IF DEV
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
//// END IF

session_start();

require_once '../src/Router/routing.php';
