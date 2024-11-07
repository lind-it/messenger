export function createMessageElement(data)
{
    cosole.log(data);
    let chatRoomBody = document.querySelector('#chat-room').querySelector('#chat-room-body');

    let owner = data.owner ? 'my' : 'their';

    chatRoomBody.insertAdjacentHTML('beforeEnd',
`
        <div class="${owner}">${data.text}</div>
    `);
}