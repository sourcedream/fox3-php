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
      require_once $file; //NOSONAR
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

  '/listar-mdfe' => [
    ['get' => 'MDFEController@listmdfe'],
  ],

  '/novo-mdfe' => [
    ['get' => 'MDFEController@newMDFE'],
    ['post' => 'MDFEController@saveMDFE'],
  ],

  '/encerrar-mdfe' => [
    ['get' => 'MDFEController@closeMDFEPage'],
    ['post' => 'MDFEController@closeMDFE'],
  ],

  '/mdfe-encerrado' => [
    ['get' => 'MDFEController@mdfeClosed']
  ],

  '/' => [
    ['get' => 'HomeController@index'],
  ],
];

$server = new Server($routes);
$server->serve();
