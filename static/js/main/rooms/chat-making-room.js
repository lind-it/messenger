export function showCreateChatRoom()
{
    let createChatRoom = document.querySelector('#create-chat-room');
    createChatRoom.style.display = 'flex';
}

export function hideCreateChatRoom()
{
    let createChatRoom = document.querySelector('#create-chat-room');
    createChatRoom.style.display = 'none';
}