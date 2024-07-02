<?php

namespace Fox3;

use ReflectionClass;

class Server {

    /**
     * Routes
     * @var array
     */
    protected $routes;

    public function __construct(array $routes = []) {
        $this->routes = $routes;
    }

    public function serve() {
        $uri = @$_SERVER['REQUEST_URI'];
        $method = @$_SERVER['REQUEST_METHOD'];
        $parsed_url = parse_url($uri, PHP_URL_PATH);
        
        $controllerDispatcher = new Controller();
        
        $router = new Router();
        $choosen_route =$router->findRouteDefinition($parsed_url, $this->routes);

        if (!$this->handleMiddleware($choosen_route)) {
            header("Location: /");
            exit();
        }
        
        $response = $controllerDispatcher->dispatchCall($choosen_route, $method);
        print_r($response);
    }

    private function handleMiddleware($route) {
        $middlewares = array_column($route, 'middleware');

        if (!isset($middlewares) || empty($middlewares))
            return true;

        foreach($middlewares[0] as $middleware) {
            $reflector = new ReflectionClass('App\\Middlewares\\' . $middleware);
            $instance = $reflector->newInstance(); //TODO: Check for constructor deps
            if (!$instance->handle()) {
                return false;
            }
        }

        return true;
    }
}
