export function showChatRoom(chat)
{
    // находим комнату чата
    let chatRoom = document.querySelector('#chat-room');

    // находим элементы комнаты
    let chatRoomImg = chatRoom.querySelector('#chat-room-head-img');
    let chatRoomName = chatRoom.querySelector('#chat-room-head-name');
    let chatRoomBody = chatRoom.querySelector('#chat-room-body');

    // обновляем данные элементов комнаты
    chatRoomImg.src = chat.querySelector('img').src;
    chatRoomName.innerHTML = chat.querySelector('h3').innerHTML;
    document.forms.message.chatId.value = chat.id;

    let answer = getChatRoomMessages(chat.id);

    answer.then((data) =>
    {
        for (let i = 0; i <= data.messages; i++)
        {
            chatRoomBody.insertAdjacentHTML('beforeEnd',
        `
                <div class="${data.messages[0].owner}">data.messages[0].text</div>
            `);
        }

    });

    answer.catch((errorMessage)=>
    {
        if (errorMessage === 'нет сообщений')
        {
            chatRoomBody.insertAdjacentHTML('beforeEnd',
                `
                <h1>Начните общаться!</h1>  
            `);
        }
    });
}

function getChatRoomMessages(chatId) {
    let xhr = new XMLHttpRequest();

    xhr.open('GET', `/message/get-messages?chat_id=${chatId}`);
    xhr.send();

    return new Promise((resolve, reject)=>
    {
        xhr.onload = ()=>
        {
            if (xhr.status >= 400)
            {
                alert('При загрузке сообщений произошла ошибка, перезагрузите страницу');
                reject();
            }

            else
            {
                let answer = JSON.parse(xhr.response);
                console.log(answer);

                if (answer.status === 'error')
                {
                    reject(answer.message);
                }

                else if (answer.status === 'success')
                {
                    resolve(answer.data);
                }
            }
        }
    });
}

export function createMessage()
{

}
