<?php
if(isset($_POST['cookie']))
{
    session_id($_POST['cookie']);
}
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/routes/web.php';
