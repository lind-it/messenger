export function intoRoom(websock, chatId)
{
    let message =
        {
            'type': 'ws-data',
            'action': 'into-room',
            'data':
                {
                    "chatId": chatId
                }
        };

    websock.send(message);
}