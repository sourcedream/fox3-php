<?php

namespace App\Controllers;

use App\Fox3\Controller;

class UsersController extends Controller {
    public function index() {
        return $this->view('users/index.php', ['nome' => "Sip"]);
    }
}