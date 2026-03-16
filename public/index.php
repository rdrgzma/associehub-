<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');


require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';
require_once '../config/database.php';

// Simple autoloader for controllers and models
spl_autoload_register(function($className) {
    if (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
    } elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    }
});

$router = new Router();
require_once '../routes/web.php';
$router->dispatch();
