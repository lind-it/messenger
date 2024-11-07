document.addEventListener('DOMContentLoaded', ()=>
{
    let form = document.querySelector('#form');

    form.addEventListener('submit', validate);
})

function validate(e)
{
    e.preventDefault();

    let form = e.target;

    if(form.password.value.length < 1 || form.email.value.length < 1)
    {
        alert('заполните все поля');
    }

    else
    {
        form.submit();
    }
}