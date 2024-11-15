export function createMessageElement(data)
{
    let chatList = document.querySelector('.chatList');
    let chats = chatList.querySelectorAll('.chat');

    for (let i = 0; i < chats.length; i++)
    {
        if (data.chat_id == chats[i].id)
        {
            chats[i].querySelector('.last-message').innerHTML = data.short_text;

            if (chats[i].className.includes('chosen'))
            {
                let chatRoomBody = document.querySelector('#chat-room').querySelector('#chat-room-body');

                let owner = data.owner ? 'my' : 'their';

                chatRoomBody.insertAdjacentHTML('beforeEnd',
                    `
                            <div class="${owner}">${data.text}</div>
                        `);
            }
        }
    }
}