<?php

namespace App\Controllers;

use Fox3\Controller;

class HomeController extends Controller {
    public function index() {
        $this->redirect('/encerrar-mdfe');
    }
}
