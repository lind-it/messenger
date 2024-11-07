<?php

namespace App\Controllers;

use App\Models\Message;
use App\Services\Database\QueryBuilderError as QBError;

class MessageController extends Controller
{
    public function getMessages()
    {

        try
        {
            $messages = Message::table()
                ->query()
                ->find([
                    ['chat_id', '=', $_GET['chat_id']]
                ]);

            if (!$messages)
            {
                $answer['status'] = 'error';
                $answer['message'] = 'нет сообщений';

                return json_encode($answer);
            }
        }
        catch (QBError $e)
        {
            $answer['status'] = 'error';
            $answer['message'] = $e->getErrorMessage(); // при получении чатов произошла ошибка;

            return json_encode($answer);
        }

        $answer['status'] = 'success';

        return json_encode($answer);

    }

    public function create()
    {
        try
        {
            $message = Message::table()
                            ->query()
                            ->create(
                                [
                                    'text' => $_POST['text'],
                                    'time' => (new \DateTime())->format('Y-m-d H:i:s'),
                                    'user_id' => $_SESSION['user_id'],
                                    'chat_id' => $_POST['chat_id']
                                ]
                            );

            if (!$message)
            {
                $answer['status'] = 'error';
                $answer['message'] = 'Ошибка отправки сообщения';

                json_encode($answer);
            }
        }
        catch (QBError $e)
        {
            $answer['status'] = 'error';
            $answer['message'] = $e->getErrorMessage(); // при получении чатов произошла ошибка;

            return json_encode($answer);
        }

        $answer['status'] = 'success';
        $answer['target'] = 'message';
        $answer['data']['text'] = $message->text;
        $answer['data']['time'] = $message->time;
        $answer['data']['chat_id'] = $message->chat_id;

        return json_encode($answer);
    }
}