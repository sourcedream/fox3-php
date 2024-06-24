<?php

namespace App\Fox3;

use Fox3\Template;
use Exception;
use ReflectionClass;
use ReflectionObject;

class Controller {

    public function dispatchCall($routeData, $method) {
        list($class, $function) = $this->findControllerMethod($routeData, $method);

        $controller = $this->instatiateController($class);
        return $controller->call($controller, $function);
    }

    protected function instatiateController($class) {
        $instance = new ReflectionClass('App\\Controllers\\' . $class);
        return $instance->newInstance(); //TODO: Check for constructor deps
    }

    public function call($controller, $function) {
        if (!method_exists($controller, $function)) {
            throw new Exception("method not found in controller");
        }
        
        $functionInstance = (new ReflectionObject($controller))->getMethod($function);
        return $functionInstance->invokeArgs($this, []); //TODO: Add paremeters here
    }

    /**
     * Find the desired Controller and its method
     * @param array $routeData Data is gained from route file
     * @param string $method Is the current HTTP Method
     * @return array
     */
    protected function findControllerMethod($routeData, $method) : array
    {
        $actions = array_column($routeData, strtolower($method));
        list($class, $function) = explode('@', $actions[0]);

        if (empty($class)) {
            throw new Exception("Invalid route");
        }

        return [$class, $function];
    }

    protected function view(string $template_name, array $data = []) : string {
        $template = new Template($template_name, $data);
        return $template->render();
    }
}
