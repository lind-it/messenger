import {createMessageElement} from './ws-functions/create-message-element.js';

export function wsConnectionInit()
{
    let socket = new WebSocket("ws://127.0.0.1:8624");

    socket.onopen = function(e)
    {
        alert("[open] Соединение установлено");
    };

    socket.onmessage = function(event)
    {
        // создаем перечисление целей, в соответствии с которыми будт вызываться функции
        let targets = {'message': createMessageElement};

        let answer = JSON.parse(event.data);

        if (answer.status === 'success')
        {
            targets[answer.target](answer.data);
        }

    };

    socket.onclose = function(event)
    {
        if (event.wasClean)
        {
            alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
        }

        else
        {
            // например, сервер убил процесс или сеть недоступна
            // обычно в этом случае event.code 1006
            alert('[close] Соединение прервано');
        }
    };

    socket.onerror = function(error)
    {
        alert(`[error]`);
    };

    return socket;
}
