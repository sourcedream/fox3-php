<?php 

namespace App\Middlewares;

class AuthMiddleware {

    public function handle()  {
        $login = $_SESSION['authenticated'];
        return isset($login) && !empty($login);
    }
}