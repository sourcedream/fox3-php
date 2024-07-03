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

        if ($user->senha != $this->hashPassword($_POST['PASSWORD'])) {
            return $this->view('login.php', ['aviso' => 'Usuário ou senha inválidos']);
        }

        $_SESSION['authenticated'] = true;
        $this->redirect('/listar-mdfe');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }

    public function password() {
        return $this->view('users/change_password.php');
    }

    public function change() {
        $password = $_POST['PASSWORD'];
        $password_repeat = $_POST['PASSWORD_REPEAT'];

        if ($password != $password_repeat) {
            return $this->view('users/change_password.php', ['aviso' => 'As senhas não conferem']);
        }

        $new_password = $this->hashPassword($password);

        $this->updatePassword($new_password);

        $_SESSION['success'] = 'Senha alterada com sucesso';
        return $this->redirect('/listar-mdfe');
    }

    private function hashPassword($password) {
        return md5(md5(md5($password)));
    }

    private function getUser($USERNAME) {
        $con = Mysql::conn();
        $stm = $con->prepare('SELECT * FROM usuarios WHERE usuario = ?');
        $stm->bind_param('s', $USERNAME);
        $stm->execute();
        $result = $stm->get_result();
        return $result->fetch_object();
    }

    private function updatePassword($new_password) {
        $con = Mysql::conn();
        $stm = $con->prepare('UPDATE usuarios SET senha = ?');
        $stm->bind_param('s', $new_password);
        $stm->execute();
        return $stm->affected_rows;
    }
}
