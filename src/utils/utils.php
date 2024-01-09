<?php
namespace utils;
class utils{
    public static function deleteSession($nombreSession){
        if (isset($_SESSION[$nombreSession])){
            $_SESSION[$nombreSession]=null;
            unset($_SESSION[$nombreSession]);
        }
    }
}
