<?php

namespace App\Controllers;

use Fox3\Controller;
use Fox3\Helpers\Mysql;

class LoginController extends Controller {
    public function index() {
        return $this->view('login.php');
    }

    public function login() {
        $user = $this->getUser($_POST['USERNAME']);

        if (!isset($user)) {
            return $this->view('login.php', ['aviso' => 'Usuário ou senha inválidos']);
        }

        if ($user->senha != md5(md5(md5($_POST['PASSWORD'])))) {
            return $this->view('login.php', ['aviso' => 'Usuário ou senha inválidos']);
        }

        $_SESSION['authenticated'] = true;
        $this->redirect('/listar-mdfe');
    }

    private function getUser($USERNAME) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM usuarios WHERE usuario = ?');
        $stm->bind_param('s', $USERNAME);
        $stm->execute();
        $result = $stm->get_result();
        return $result->fetch_object();
    }
}
