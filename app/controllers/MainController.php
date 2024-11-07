<?php

namespace App\Controllers;

class MainController extends Controller
{
    public function index()
    {
        if(empty($_SESSION['name']) || empty($_SESSION['email']))
        {
            $this->redirect('auth/sign-in');
        }

        return $this->view('index');
    }
}