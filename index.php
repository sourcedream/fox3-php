<?php
namespace App;
require_once 'vendor/autoload.php'; //NOSONAR

spl_autoload_register(function ( $class_name ) {
  $file = __DIR__.'\\'. str_replace('App\\', '', $class_name) . '.php';

  if ( file_exists($file) ) {
      require_once $file; //NOSONAR
  }

});

use Fox3\Server;

$routes = [
  '/usuarios' => [
    ['get' => 'UsersController@index'],
    ['post' => 'Controllers/Users@create'],
  ],
  '/usuarios/{id}' => [
    ['middleware' => ['AuthMiddleware']],
    ['get' => 'HomeController@showUser'],
  ],

  '/encerrar-mdfe' => [
    ['get' => 'MDFEController@closeMDFEPage'],
    ['post' => 'MDFEController@closeMDFE'],
  ],

  '/' => [
    ['get' => 'HomeController@index'],
  ],
  '/sobre' => [
    ['get' => 'HomeController@sobre'],
  ],
];

$server = new Server($routes);
$server->serve();
