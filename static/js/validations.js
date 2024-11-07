function validateSignInForm(e)
{
    e.preventDefault();

    let form = e.target;

    if(form.name.value.length < 1 || form.email.value.length < 1)
    {
        alert('заполните все поля');
    }

    if (form.password.value.length <= 3)
    {
        alert('Слишком маленький пароль');
        return;
    }

    if (form.password.value !== form.conf_pass.value)
    {
        alert('Пароли не совпадают');
        return;
    }

    else
    {
        form.submit();
    }
}