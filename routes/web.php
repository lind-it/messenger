<?php

use App\Services\Router;
use App\Controllers\MainController;
use App\Controllers\AuthController;
use App\Controllers\ChatController;
use App\Controllers\ProfileController;
use App\Controllers\MessageController;

// определяем главный маршрут
Router::route('GET', '/', [MainController::class, 'index']);

// определяем маршруты для авторизации
Router::route('GET', '/auth/sign-up', [AuthController::class, 'signUp']);
Router::route('GET', '/auth/sign-in', [AuthController::class, 'signIn']);
Router::route('POST', '/auth/register', [AuthController::class, 'register']);
Router::route('POST', '/auth/login', [AuthController::class, 'login']);

// определяем маршруты для работы с пользователем
Router::route('GET', '/auth/exit', [ProfileController::class, 'exit']);
Router::route('GET', '/auth/get-profile', [ProfileController::class, 'getProfile']);
Router::route('POST', '/auth/update-profile', [ProfileController::class, 'updateProfile']);

// определяем маршруты для работы с чатами
Router::route('GET', '/chat/get-chats-list', [ChatController::class, 'getChatsList']);
Router::route('POST', '/chat/create', [ChatController::class, 'create']);
Router::route('DELETE', '/chat/delete', [ChatController::class, 'delete']);

//определяем маршруты для работы с сообщениями
Router::route('GET', '/message/get-messages', [MessageController::class, 'getMessages']);
Router::route('POST', '/message/create', [MessageController::class, 'create']);


Router::enable();
