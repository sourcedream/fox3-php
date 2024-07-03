<?php
namespace App;
require_once 'vendor/autoload.php'; //NOSONAR

spl_autoload_register(function ( $class_name ) {
  $file = __DIR__.'\\'. str_replace('App\\', '', $class_name) . '.php';

  if ( file_exists($file) ) {
      require_once $file; //NOSONAR
  }

});

putenv('DBUSER=root');
putenv('DBNAME=mdfes');

use Fox3\Server;

$routes = [
  '/login' => [
    ['get' => 'LoginController@index'],
    ['post' => 'LoginController@login'],
  ],

  '/sair' => [
    ['middleware' => ['AuthMiddleware']],
    ['get' => 'LoginController@logout'],
  ],

  '/trocar-senha' => [
    ['middleware' => ['AuthMiddleware']],
    ['get' => 'LoginController@password'],
    ['post' => 'LoginController@change'],
  ],

  '/listar-mdfe' => [
    ['middleware' => ['AuthMiddleware']],
    ['get' => 'MDFEController@listmdfe'],
  ],

  '/novo-mdfe' => [
    ['middleware' => ['AuthMiddleware']],
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
