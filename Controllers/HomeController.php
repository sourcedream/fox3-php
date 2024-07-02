<?php

namespace App\Controllers;

use Fox3\Controller;

class HomeController extends Controller {
    public function index() {
        return $this->view('home.php');
    }

    public function sobre() {
        return "Sobre";
    }

    // Private route
    public function showUser() {
        return "oi usuario";
    }
}
