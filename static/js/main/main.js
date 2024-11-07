import {sendMessage} from './ws-functions/send-message.js';
import {getChats, createChatElement, createChat} from './chat-list-functions.js';
import {hideRoom, showRoom} from './rooms.js';
import {uploadProfileData, changeProfile} from './rooms/profile-room.js';
import {wsConnectionInit} from './wsInit.js';
import {showChatRoom} from './rooms/chat-room.js';
import {Chat} from './classes/Chat.js';

document.addEventListener('DOMContentLoaded', ()=>
{
    init();
});

function init()
{
    // находим элементы
    let createChatBtn = document.querySelector('#createChat');
    let profileBtn = document.querySelector('#profile');
    let chatList = document.querySelector('#chatList');
    let createChatForm = document.querySelector('#create-chat');
    let changeProfileBtn = document.querySelector('#change-profile');
    let sendMessageForm = document.forms.message;
    let websocket = wsConnectionInit();

    // создаем список чатов
    let chats = [];

    //навешиваем обработчики собитий
    //при нажатии на кнопку плюса показываем форму создания чатов
    createChatBtn.addEventListener('click', () =>
    {
        hideRoom('#chat-room');
        hideRoom('#profile-room');
        showRoom('#create-chat-room', 'flex');
    });

    //при нажатии на кнопку профиля показываем профиль
    profileBtn.addEventListener('click', ()=>
    {
        hideRoom('#chat-room');
        hideRoom('#create-chat-room');
        showRoom('#profile-room', 'flex', uploadProfileData);
    });

    // создаем чаты из полученных данных
    getChats().then((chatData)=>
    {
        for(let i = 0; i < chatData.length; i++)
        {
            let chatElement = createChatElement(chatData[i], chats);

            // при нажатии на чат преходим в комнату чата
            chatElement.addEventListener('click', (e)=>
            {
                e.stopPropagation();

                // инициализируем функцию, чтобы передать в функцию, которую она вызывает параметр e
                let callBack = () =>
                {
                    showChatRoom(e.currentTarget)
                }

                hideRoom('#profile-room');
                hideRoom('#create-chat-room');
                showRoom('#chat-room', 'block', callBack);
            });

            chatList.insertAdjacentElement('afterbegin', chatElement);
        }
    });
    console.log(chats);
    createChatForm.addEventListener('submit', createChat);
    changeProfileBtn.addEventListener('click', changeProfile);
    sendMessageForm.addEventListener('submit', (e) =>
    {
        e.preventDefault();

        let form = e.target;
        sendMessage(form, websocket);
    });
}