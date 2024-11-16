<?php

namespace App\Controllers;

use App\Models\Chat;
use App\Models\Member;
use App\Models\Message;
use App\Models\User;
use App\Services\Database\QueryBuilderError;
use App\Services\Database\QueryBuilderError as QBError;

class ChatController extends Controller
{
    public function getChatsList()
    {
        try
        {
            $chats = Chat::table()
                         ->query()
                         ->customQuery('
                            select u.name, u.avatar, c.id
                            from users as u
                            join members as m on u.id = m.user_id
                            join chats as c on m.chat_id = c.id
                            where m.user_id != '.  $_SESSION['user_id'] . '
                            and c.id in (select chat_id from members where user_id = ' . $_SESSION['user_id'] . ')'
                         );
        }

        catch(QBError $e)
        {
            $answer['status'] = 'error';
            $answer['message'] = $e->getErrorMessage(); // при получении чатов произошла ошибка;

            return json_encode($answer);
        }

        $func = function ($chat)
        {
                // находим текст и отправителя последнего сообщения
            $lastMessage = Message::table()
                                    ->query()
                                    ->customQuery('
                                    select u.name as last_message_user, m.text as last_message_text 
                                    from users as u
                                    join messages as m on u.id = m.user_id 
                                    where m.chat_id = ' . $chat['id'] . '
                                    and m.time = (select max(time) from messages where chat_id = ' . $chat['id'] . ')');

            if (!is_null($lastMessage))
            {
                $chat['last_message'] = $lastMessage;
            }

            return $chat;
        };

        // добавляем чатам последнее сообщение
        $chats = array_map($func, $chats);

        $answer['status'] = 'success';

        $answer['data'] = $chats;

        return json_encode($answer);
    }

    public function create()
    {
        $postData = json_decode(file_get_contents('php://input'), true);

        if($postData['member'] === $_SESSION['name'])
        {
            $answer['status'] = 'error';
            $answer['message'] = 'Нельзя создать чат с самим собой';

            return json_encode($answer);
        }

        try
        {
            // находим собеседника
            $companion = User::table()
                                ->query()
                                ->find([['name', '=', $postData['member']]]);

            if(!$companion)
            {
                $answer['status'] = 'error';
                $answer['message'] = 'Пользователя с таким именем не существует';

                return json_encode($answer);
            }

            Chat::table()->startTransaction();

            // создаем чат
            $chat = Chat::table()
                            ->query()
                            ->create(
                                [
                                    'name' => $postData['chatName']
                                ]);

            // создаем первого участника чата
            Member::table()
                    ->query()
                    ->create(
                        [
                            'chat_id' => $chat->id,
                            'user_id' => $_SESSION['user_id']
                        ]
                    );

            // создаем второго участника чата
            Member::table()
                    ->query()
                    ->create(
                        [
                            'chat_id' => $chat->id,
                            'user_id' => $companion->id
                        ]
                    );

            Chat::table()->commit();
        }

        catch (QBError $e)
        {
            Chat::table()->rollback();

            $errorCode = $e->getErrorCode();

            $answer['status'] = 'error';

            if ($errorCode === '23505')
            {
                $answer['message'] = 'Чат с таким именем уже существует';
            }

            else
            {
                $answer['message'] = $e->getErrorMessage();
            }

            header('Content-Type: application/json; charset=utf-8');
            return json_encode($answer);
        }

        $answer['status'] = 'success';
        $answer['message'] = 'success';

        echo  json_encode($answer);

    }

    public function delete()
    {
        echo 'deleted';
    }
}