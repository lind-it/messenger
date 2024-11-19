export function exitRoom(websock)
{
    let message =
        {
            'type': 'ws-data',
            'action': 'exit-room'
        };

    websock.send(message);
}