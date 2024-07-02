<?php

if (!function_exists('flashMessage')) {

    function flashMessage(string $message_name)
    {
        if(isset($_SESSION[$message_name])) {
            $message = $_SESSION[$message_name];
            unset($_SESSION[$message_name]);
            return $message;
        }

        return '';
    }
}
