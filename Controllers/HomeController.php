<?php

namespace App\Controllers;

use App\Fox3\Controller;

class HomeController extends Controller {
    public function index() {
        return $this->view('home.php');
    }

    public function showUser() {
        return "oi";
    }
}
