export function getChats()
{
    let xhr = new XMLHttpRequest();

    xhr.open('GET', '/chat/get-chats-list');
    xhr.send();

    return new Promise((resolve, reject)=>
    {
        xhr.onload = ()=>
        {
            if (xhr.status >= 400)
            {
                alert('При звагурзке ваших чатов произошла ошибка, перезагрузите страницу');
                reject();
            }

            else
            {
                let answer = JSON.parse(xhr.response);

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

export function createChatElement(chatData)
{
    let chatName = chatData.name;
    let chatImg = chatData.avatar;
    let lastMessageText = chatData.last_message[0]['last_message_text'];
    let lastMessageUser = chatData.last_message[0]['last_message_user'];
    let chatElement = document.createElement('div');

    chatElement.className = 'chat';
    chatElement.id = chatData.id

    chatElement.innerHTML =
    `
        <img src="static/img/avatars/${chatImg}" alt="">
        <div class="chat-body">
            <h3>${chatName}</h3>
            <div class="chat-body-bottom">
                <div class="last-message">
                    <div style="display: inline-block; color: #9aa0ca">
                        <strong>${lastMessageUser}:</strong>
                    </div> 
                    <div style="margin-left: 4px;">${lastMessageText}</div>
                </div>
            </div>
        </div>
    `;

    return chatElement;
}

export function createChat(e)
{
    e.preventDefault();

    let form = e.target;

    let xhr = new XMLHttpRequest();

    xhr.open('POST', '/chat/create');

    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

    let formData = {};

    formData.chatName = form.chatName.value;
    formData.member = form.userName.value;

    xhr.send(JSON.stringify(formData));

    xhr.onload = ()=>
    {
        if (xhr.status >= 400)
        {
            alert('Не удалось создать чат, попробйте еще раз');
            return false;
        }

        let answer = JSON.parse(xhr.response);

        if (answer.status === 'error')
        {
            alert(answer.message);
            return false;
        }

        console.log(answer)
        return true;
    }

    xhr.onerror = ()=>
    {
        alert('Не удалось создать чат, попробйте еще раз');
        return false;
    }
}

