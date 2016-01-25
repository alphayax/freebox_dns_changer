<?php
namespace alphayax;
use alphayax\freebox\api\v3\DHCP;

require_once 'autoload.php';
AYX_Autoloader::Register();


$Auth  = new freebox\api\v3\Authorize();
$Login = new freebox\api\v3\Login( $Auth->getAppToken());

$Login->ask_login_status();
$Login->create_session();

$Config = new DHCP( $Login->getSessionToken());
$Config->get_current_configuration();
$Config->set_attribute_configuration('ip_range_end','192.168.0.51');
$Config->get_current_configuration();
