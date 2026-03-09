<?php

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $viewFile);
        }
    }

    protected function redirect($url) {
        $base = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        header('Location: ' . $base . $url);
        exit;
    }
}
