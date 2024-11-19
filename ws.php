<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Ws implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msgData = json_decode($msg, true);

        // Переделать, слишком большая вложенность
        if ($msgData['type'] === 'ws-data')
        {
            // если пользователь вошел в конату, то его соединению присваевается свойство chatId
            if ($msgData['action'] === 'into-room')
            {
                foreach ($this->clients as $client)
                {
                    if ($from === $client)
                    {
                        $client->chatId = $msgData['data']['chatId'];
                    }
                }
            }

            else if ($msgData['action'] === 'exit-room')
            {
                foreach ($this->clients as $client)
                {
                    if ($from === $client)
                    {
                        unset($client->chatId);
                    }
                }
            }
        }

        // Определение параметров сеанса
        $curlOptions = array(
            CURLOPT_URL => 'http://messenger/' . $msgData['trigger'] . '/' . $msgData['action'],
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTPHEADER => array('Content-Type' => 'multipart/form-data'),
            CURLOPT_POSTFIELDS => $msgData['data']
        );

        // Инициализация сеанса
        $ch = curl_init();

        // установка параметров сеанса
        curl_setopt_array($ch, $curlOptions);

        // Выполнение запроса, в переменной хранится ответ от сервера
        $data = curl_exec($ch);
        $data = json_decode($data, true);

        foreach ($this->clients as $client)
        {
            if ($from !== $client)
            {
                $data['data']['owner'] = false;
                $client->send(json_encode($data));
            }

            else
            {
                $data['data']['owner'] = true;
                $client->send(json_encode($data));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Ws()
        )
    ),
    8624
);

$server->run();