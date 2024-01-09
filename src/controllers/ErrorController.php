<?php

namespace controllers;
use lib\Pages;
class ErrorController{
    public static function showErrorView()
    {
        $pages=new Pages();
        $pages->render('error/errorView');
    }
    public static function show_error404():string{
        return "<p>La página que buscas no existe </p>";
    }
}