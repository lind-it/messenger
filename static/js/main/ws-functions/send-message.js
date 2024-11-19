export function sendMessage(form, websock)
{
    let message =
        {
            'type': 'request',
            'trigger': 'message',
            'action': 'create',
            'data':
                {
                    'text': form.text.value,
                    'chat_id': form.chatId.value,
                    'cookie': document.cookie.split().find(element => element.includes('PHPSESSID')).split('=')[1]
                }
        };

    websock.send(JSON.stringify(message));
}