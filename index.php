<?php
namespace App;
require 'vendor/autoload.php';

spl_autoload_register(function ( $class_name ) {
  $file = __DIR__.'\\'. str_replace('App\\', '', $class_name) . '.php';

  if ( file_exists($file) ) {
      require_once $file; //NOSONAR
  }

});

putenv('DBUSER=root');
putenv('DBNAME=mdfes');

session_start();

use Fox3\Server;

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
