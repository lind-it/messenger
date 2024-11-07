export function changeProfile(e)
{
    e.preventDefault()

    // находим элемент профиля
    let profileRoom = document.querySelector('#profile-room');

    // находим элементы внутри профиля
    let profileMain = profileRoom.querySelector('#profile-room-main');
    let profileOptions = profileRoom.querySelector('#profile-room-options');

    //находим старые значения профиля
    let oldImg = profileMain.querySelector('#profile-room-main-img').src;
    let oldName = profileMain.querySelector('#profile-room-main-name').innerHTML;

    // выключаем эти элементы
    profileMain.style.display = 'none';
    profileOptions.style.display = 'none';

    // создаем форму
    let form = document.createElement('form');

    // даем форме айди
    form.id = 'change-profile-form';

    // даем форме стили
    form.className = 'profile-change-form';

    // создаем тело формы
    form.innerHTML =
    `
        <label class="avatar-input">
            <img src="${oldImg}" alt="">
            <input name="newImg" type="file" accept="image/*">
        </label>
        
        <label for="newName">Введите новое имя:</label>
        <input class="name-change-input" name="newName" type="text" placeholder="Введите новое имя" value="${oldName}">
        
        <input id="saveBtn" class="profile-change-save" type="submit" value="Сохранить">
    `;

    // отображаем загруженное изображение сразу
    form.newImg.addEventListener('change', (e)=>
    {
        let newImgUrl = URL.createObjectURL(e.target.files[0]);
        form.querySelector('img').src = newImgUrl;
    });

    form.querySelector('#saveBtn').addEventListener('click', (e) =>
    {
        e.preventDefault();

        sendNewData().then(()=>
        {
            form.remove();

            profileMain.style.display = 'flex';
            profileOptions.style.display = '';

            uploadProfileData();
        })

    });

    profileRoom.insertAdjacentElement('afterbegin', form);
}

function sendNewData()
{

    let xhr = new XMLHttpRequest();

    xhr.open('POST', 'auth/update-profile');

    let form = document.querySelector('#change-profile-form');

    let formData = new FormData(form);

    return new Promise((resolve, reject) =>
    {
        xhr.send(formData);

        xhr.onload = ()=>
        {

            if (xhr.status >= 400)
            {
                alert('не удалось обовить ваш профиль');
                reject();
            }

            let answer = JSON.parse(xhr.response);

            if (answer.status === 'error')
            {
                alert(answer.message);
                reject()
            }

            resolve();
        }
    });
}


export function uploadProfileData()
{
    let xhr = new XMLHttpRequest();

    xhr.open('GET', '/auth/get-profile');

    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

    xhr.send();

    xhr.onload = ()=>
    {
        let elem = document.querySelector('#profile-room-main');

        if (xhr.status >= 400)
        {

            elem.innerHTML =
            `
                <p style="color:red; font-size: 15px">
                    Не удалось загрузить данные вашего профиля
                </p>
            `;

            return false;
        }


        let answer = JSON.parse(xhr.response);

        if (answer.status === 'error')
        {
            elem.innerHTML =
            `
                <p style="color:red; font-size: 15px">
                    Не удалось загрузить данные вашего профиля
                </p>
            `;
            alert(answer.message);
            return false;
        }

        let profileImg = document.querySelector('#profile-room-main-img');
        let profileName = document.querySelector('#profile-room-main-name');

        profileImg.src = `/static/img/avatars/${answer.body.img}`;
        profileName.innerHTML = answer.body.name;
    }
}

