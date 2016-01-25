<?php
namespace alphayax;

require_once 'autoload.php';
AYX_Autoloader::Register();


/*
require_once 'rest.php';
require_once 'Authorize.php';
require_once 'Login.php';
*/

$Auth  = new freebox\Authorize();
$Login = new freebox\Login( $Auth->getAppToken());

$Login->ask_login_status();
$Login->create_session();


