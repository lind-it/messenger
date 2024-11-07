<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Database\QueryBuilderError as QBError;

class ProfileController extends Controller
{
    public function getProfile()
    {
        try
        {
            //находим даные пользователя
            $user = User::table()
                ->query()
                ->find([['id', '=', $_SESSION['user_id']]]);

        }

        catch(QBError $e)
        {
            $answer['status'] = 'error';
            $answer['message'] = $e->getErrorMessage();

            return json_encode($answer);
        }

        if(!$user)
        {
            // если профиль не найден, просим пользователя войти еще раз
            $answer['status'] = 'error';
            $answer['message'] = 'Данные вышего профиля не найдены, войдите еще раз';

            return json_encode($answer);
        }

        //если запрос успешен, то отправляем на клиент
        $answer['status'] = 'success';
        $answer['body'] = [
            'img' => $user->avatar,
            'name' => $user->name
        ];

        return json_encode($answer);
    }

    public function updateProfile()
    {
        $newImg = $_FILES['newImg'];
        $newImg['name'] = time() . $newImg['name'];
        $imgError = $newImg['error'];

        if($imgError == 0)
        {
            $newData['avatar'] = $newImg['name'];
        }

        else
        {
            switch ($imgError)
            {
                case 4:
                    break;

                case 1:
                case 2:
                    $answer['status'] = 'error';
                    $answer['message'] = 'Ваша картинка слишком большая';
                    return json_encode($answer);

                case 3:
                case 5:
                case 6:
                case 7:
                case 8:
                    $answer['status'] = 'error';
                    $answer['message'] = 'Ваша картинка не загрузилась попробуйте еще раз';
                    return json_encode($answer);
            }
        }

        if(!empty($_POST['newName']))
        {
            $newData['name'] = $_POST['newName'];
        }

        if (!isset($newData))
        {
            $answer['status'] = 'error';
            $answer['message'] = 'Пожалуйста, заполните хотя бы одно поле';
            return json_encode($answer);
        }

        try
        {
            move_uploaded_file($newImg['tmp_name'], __DIR__ . '/../../static/img/avatars/' . $newImg['name']);

            // находиим старую аватарку
            $oldImg = User::table()
                            ->query()
                            ->find([['id', '=', $_SESSION['user_id']]])
                            ->avatar;

            // обновялем данные профиля
            User::table()
                ->query()
                ->update($newData,
                [['id', '=', $_SESSION['user_id']]]);

            //удаляем старую автарку

            if($oldImg !== 'profile.png' && file_exists(__DIR__ . '/../../static/img/avatars/' . $oldImg))
            {
                unlink(__DIR__ . '/../../static/img/avatars/' . $oldImg);
            }

        }

        catch(QBError $e)
        {
            $answer['status'] = 'error';
            $answer['message'] = $e->getErrorMessage();

            return json_encode($answer);
        }

        $answer['status'] = 'success';

        return json_encode($answer);
    }

    public function exit()
    {
        session_unset();

        $this->redirect('/');
    }
}
