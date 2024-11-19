import {sendMessage} from './ws-functions/send-message.js';
import {getChats, createChatElement, createChat} from './chat-list-functions.js';
import {hideRoom, showRoom} from './rooms.js';
import {uploadProfileData, changeProfile} from './rooms/profile-room.js';
import {wsConnectionInit} from './wsInit.js';
import {loadChatRoomData} from './rooms/chat-room.js';
import {intoRoom} from "./ws-functions/intoRoom";
import {exitRoom} from "./ws-functions/exitRoom";

document.addEventListener('DOMContentLoaded', ()=>
{
    uploadProfileData();
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

    //навешиваем обработчики собитий
    //при нажатии на кнопку плюса показываем форму создания чатов
    createChatBtn.addEventListener('click', () =>
    {
        hideRoom('#chat-room', ()=> {
            // убираем показанные сообщения
            document.querySelector('.chat-room-body').innerHTML = '';
            document.querySelector('.chosen').className.replace('chosen', '');

            exitRoom(websocket);
        });
        hideRoom('#profile-room');
        showRoom('#create-chat-room', 'flex');
    });

    //при нажатии на кнопку профиля показываем профиль
    profileBtn.addEventListener('click', ()=>
    {
        hideRoom('#chat-room', ()=> {
            // убираем показанные сообщения
            document.querySelector('.chat-room-body').innerHTML = '';
            document.querySelector('.chosen').className.replace('chosen', '');

            exitRoom(websocket);
        });
        hideRoom('#create-chat-room');
        showRoom('#profile-room', 'flex', uploadProfileData);
    });

    // создаем чаты из полученных данных
    // а также навешиваем на них обработчики событий
    getChats().then((chatData)=>
    {
        for(let i = 0; i < chatData.length; i++)
        {
            let chatElement = createChatElement(chatData[i]);

            // при нажатии на чат преходим в комнату чата
            chatElement.addEventListener('click', (e)=>
            {
                e.stopPropagation();
                let thisChat = e.currentTarget;

                // инициализируем функцию, чтобы передать в функцию, которую она вызывает, параметр e
                let callBack = () =>
                {
                    // убираем показанные сообщения
                    document.querySelector('.chat-room-body').innerHTML = '';

                    // убираем с комнаты показатель того, что пользователь находится в ней
                    let chosenChat = document.querySelector('.chosen');

                    if (chosenChat !== null)
                    {
                        chosenChat.className = chosenChat.className.replace(' chosen', '');
                    }

                    // даем комнате показатель того, что пользователь находится в ней
                    thisChat.className += ' chosen';

                    intoRoom(websocket, thisChat.id);
                    loadChatRoomData(thisChat);
                }

                hideRoom('#profile-room');
                hideRoom('#create-chat-room');
                showRoom('#chat-room', 'block', callBack);
            });

            chatList.insertAdjacentElement('afterbegin', chatElement);
        }
    });

    createChatForm.addEventListener('submit', createChat);
    changeProfileBtn.addEventListener('click', changeProfile);
    sendMessageForm.addEventListener('submit', (e) =>
    {
        e.preventDefault();

        let form = e.target;
        sendMessage(form, websocket);
    });
}

