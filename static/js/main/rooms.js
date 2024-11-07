export function hideRoom(room, callback = ()=>{})
{
    let element = document.querySelector(room);
    element.style.display = 'none';
    callback();
}

export function showRoom(room, display, callback = ()=>{})
{
    let element = document.querySelector(room);
    element.style.display = display;
    callback();
}
