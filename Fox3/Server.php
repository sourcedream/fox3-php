<?php

namespace App\Fox3;

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
        
        $response = $controllerDispatcher->dispatchCall($choosen_route, $method);
        print_r($response);
    }
}
