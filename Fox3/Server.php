<?php

namespace App\Fox3;

require_once('Controller.php');
require_once('Router.php');

class Server {

    /**
     * Routes
     * @var Array
     */
    protected $routes;

    public function __construct(Array $routes = []) {
        $this->routes = $routes;
    }

    public function serve() {
        $URI = @$_SERVER['REQUEST_URI'];
        $METHOD = @$_SERVER['REQUEST_METHOD'];
        $parsed_url = parse_url($URI, PHP_URL_PATH);
        
        $controllerDispatcher = new Controller();
        
        $router = new Router();
        $choosen_route =$router->findRouteDefinition($parsed_url, $this->routes);
        
        $response = $controllerDispatcher->dispatchCall($choosen_route, $METHOD);
        print_r($response);
    }
}