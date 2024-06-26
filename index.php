<?php
namespace App;

/**
 * Simple autoloader
 *
 * @param $class_name - String name for the class that is trying to be loaded.
 */
spl_autoload_register(function ( $class_name ) {
  $file = __DIR__.'\\'. str_replace('App\\', '', $class_name) . '.php';

  if ( file_exists($file) ) {
      require_once $file;
  }

});

use App\Fox3\Server;

$routes = [
  '/usuarios' => [
    ['get' => 'UsersController@index'],
    ['post' => 'Controllers/Users@create'],
  ],
  '/usuarios/{id}' => [
    ['get' => 'HomeController@showUser'],
  ],

  '/encerrar-mdfe' => [
    ['get' => 'MDFEController@closeMDFEPage'],
    ['post' => 'MDFEController@closeMDFE'],
  ],

  '/' => [
    ['get' => 'HomeController@index'],
  ],
];

$server = new Server($routes);
$server->serve();
