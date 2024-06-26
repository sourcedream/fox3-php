<?php
namespace App\Fox3;

class Router {
    
    public function findRouteDefinition($current_path, $routes) {
        $patternCurrentUri = preg_replace("/\d+/", '#', $current_path);

        foreach ($routes as $route => $data) {
            $route = preg_replace('/\{[a-zA-Z]+\}+/', '#', $route);
            if ($route == $patternCurrentUri) {
                return $data;
            }
        }
    }
}
