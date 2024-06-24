<?php
namespace App;

use App\Fox3\Server;

require_once('fox3/Server.php');

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

$URI = @$_SERVER['REQUEST_URI'];
$METHOD = @$_SERVER['REQUEST_METHOD'];
$parsed_url = parse_url($URI, PHP_URL_PATH);

$server = new Server($routes);
$server->serve();