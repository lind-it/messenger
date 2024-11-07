<?php

namespace App\Controllers;

class Controller
{
    protected function view(string $view)
    {
        require_once 'static/views/' . $view . '.php';
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        exit();
    }
}