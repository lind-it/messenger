<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Database\QueryBuilderError as QBError;

class AuthController extends Controller
{
    public function signUp()
    {
        return $this->view('auth/register');
    }

    public function signIn()
    {
        return $this->view('auth/auth');
    }

    public function register()
    {
        try
        {
            $user = User::table()
                        ->query()
                        ->create([
                            'name' => $_POST['name'],
                            'email'=> $_POST['email'],
                            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
                        ]);
        }

        catch (QBError $e)
        {
            // проверяем на ошибку уникальности ключа
            if ($e->error()['code'] === '23505')
            {
                $column = stripos($e->error()['message'], 'name') ? 'имя' : 'почта';

                $_SESSION['error'] = $column . ' уже существует';
                $this->redirect('/auth/sign-up');
            }

            $_SESSION['error'] = 'Произошла ошибка, повторите еще раз';
            $this->redirect('/auth/sign-up');
        }

        // запоминаем пользователя
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;

        $this->redirect('/');
    }

    public function login()
    {
        // ищем запись в таблице
        $user = User::table()
                    ->query()
                    ->find([['email', '=', $_POST['email']]]);

        // проверяем существует ли запись
        if (!$user)
        {
            $_SESSION['error'] = 'пользователь не найден';
            $this->redirect('/auth/sign-in');
        }

        // сверяем пароли
        if(password_verify($_POST['password'], $user->password))
        {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['name'] = $user->name;
            $_SESSION['email'] = $user->email;

            $this->redirect('/');
        }

        $_SESSION['error'] = 'неправильный пароль';
        $this->redirect('/auth/sign-in');
    }


}