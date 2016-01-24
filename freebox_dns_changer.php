<?php
namespace alphayax\freebox\dns_changer;
use alphayax\freebox;

require_once 'rest.php';
require_once 'Authorize.php';
require_once 'Login.php';


$Auth  = new freebox\Authorize();
$Login = new freebox\Login( $Auth->getAppToken());

$Login->ask_login_status();
$Login->create_session();


