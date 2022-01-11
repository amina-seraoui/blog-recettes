<?php

require_once '../vendor/autoload.php';

//// IF DEV
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
//// END IF

require_once '../src/Router/Router.php';
    