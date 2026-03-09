<?php

class Router {
    private array $routes = [];

    public function add($method, $uri, $action) {
        $uriStr = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $uri);
        $uriStr = '#^' . $uriStr . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uriStr,
            'action' => $action
        ];
    }

    public function get($uri, $action) {
        $this->add('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->add('POST', $uri, $action);
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $base = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }
        if (empty($uri)) {
            $uri = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['uri'], $uri, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                [$controllerName, $methodName] = $route['action'];
                
                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
