<?php
namespace App;

require_once 'Fox3/Helpers/Autloads.php'; //NOSONAR

use App\Fox3\Server;

$routes = [
  '/usuarios' => [
    ['get' => 'UsersController@index'],
    ['post' => 'Controllers/Users@create'],
  ],
  '/usuarios/{id}' => [
    ['get' => 'show user'],
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
