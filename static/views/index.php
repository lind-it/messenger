<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/static/css/main/main.css">
    <link rel="stylesheet" href="/static/css/main/chatRoom.css">
    <link rel="stylesheet" href="/static/css/main/chat.css">
    <link rel="stylesheet" href="/static/css/main/createChatForms.css">
    <link rel="stylesheet" href="/static/css/main/profileRoom.css">
    <title>Document</title>
</head>
<body>
    <div class="main">
        <div class="left">

            <div id="options" class="options">
                <button id="profile" class="profile"><img src="/static/img/avatars/profile.png" alt=""></button>
                <button id="createChat" class="plus">+</button>
            </div>

            <div class="chatList" id="chatList">

            </div>

        </div>

        <div class="right">
            <div id="profile-room" class="profile-room">
                <div id="profile-room-main" class="profile-room-main">
                    <img id="profile-room-main-img" src="/static/img/avatars/profile.png" alt="">
                    <h1 id="profile-room-main-name">NAME</h1>
                </div>

                <div id="profile-room-options" class="profile-room-options">
                    <a class="exit" href="/auth/exit">Выйти</a>
                    <a id="change-profile" class="update" href="#">Редактировать</a>
                </div>
            </div>

            <div id="create-chat-room" class="create-chat-room">
                <div class="switch-forms">
                    <button>Создать чат</button>
                    <button>Создать группу</button>
                </div>

                <form id="create-chat" class="create-chat">
                    <label for="chatName">Назовате чат</label>
                    <input type="text" name="chatName" placeholder="Назовате чат">

                    <label for="userName">С кем хотите общатся?</label>
                    <input type="text" name="userName" placeholder="С кем хотите общатся">

                    <button type="submit">создать чат</button>
                </form>

                <form class="create-group">
                    <label for="groupName">Введите название группы</label>
                    <input type="text" name="groupName" placeholder="Введите название группы">

                    <button type="submit" value="создать чат">
                </form>
            </div>

            <div id="chat-room" class="chat-room">
                <div class="chat-room-head">
                    <div>
                        <img id="chat-room-head-img" src="/static/img/avatars/profile.png" alt="">
                        <h3 id="chat-room-head-name">Name</h3>
                    </div>
                </div>

                <div id="chat-room-body" class="chat-room-body">

                </div>

                <div class="chat-room-bottom">
                    <form name="message">
                        <input type="hidden" name="chatId">
                        <textarea name="text" placeholder="напишите что-нибудь"></textarea>
                        <button type="submit">&#9658;</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <script type="module" src="/static/js/main/main.js"></script>
</body>
</html>