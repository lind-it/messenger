<?php

use App\Services\Database\DBWorker;

$config = require_once __DIR__ . '/config.php';
$db_config = $config['database'];

$dbname = $db_config['DB_DATABASE'];
$username = $db_config['DB_USERNAME'];
$password = $db_config['DB_PASSWORD'];
$host = $db_config['DB_HOST'];
$port = $db_config['DB_PORT'];

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

$connect = new PDO($dsn, $username, $password);

DBWorker::setConnect($connect);

DBWorker::create('users', [
                    'id' => ['SERIAL', 'PRIMARY KEY'],
                    'name' => ['VARCHAR(255)', 'UNIQUE', 'NOT NULL'],
                    'email' => ['VARCHAR(255)', 'UNIQUE', 'NOT NULL'],
                    'password' => ['VARCHAR(255)', 'NOT NULL'],
                    'avatar' => ['VARCHAR(255)', 'NOT NULL', "DEFAULT 'profile.png'"],
                ]);

DBWorker::create('chats', [
                    'id' => ['SERIAL', 'PRIMARY KEY'],
                    'name' => ['VARCHAR(255)', 'UNIQUE', 'NOT NULL'],
                ]);

DBWorker::create('messages', [
                    'id' => ['SERIAL', 'PRIMARY KEY'],
                    'text' => ['VARCHAR(255)', 'NOT NULL'],
                    'time' => ['TIMESTAMP WITHOUT TIME ZONE'],
                    'user_id' => ['INT', 'NOT NULL', 'REFERENCES users(id)'],
                    'chat_id' => ['INT', 'NOT NULL', 'REFERENCES chats(id)'],
                ]);

DBWorker::create('members', [
                    'id' => ['SERIAL', 'PRIMARY KEY'],
                    'user_id' => ['INT', 'NOT NULL', 'REFERENCES users(id)'],
                    'chat_id' => ['INT', 'NOT NULL', 'REFERENCES chats(id)'],
                ]);