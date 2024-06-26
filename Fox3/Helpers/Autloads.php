<?php

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

