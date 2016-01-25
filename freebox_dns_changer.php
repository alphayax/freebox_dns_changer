<?php
namespace alphayax;

require_once 'autoload.php';
AYX_Autoloader::Register();


$Auth  = new freebox\api\v3\Authorize();
$Login = new freebox\api\v3\Login( $Auth->getAppToken());

$Login->ask_login_status();
$Login->create_session();

$Config = new freebox\api\v3\DHCP( $Login->getSessionToken());
$Config->get_current_configuration();
$Config->set_attribute_configuration(['dns' => array_merge( get_nearest_servers(), ['8.8.8.8'])]);
$Config->get_current_configuration();


/**
 * @return array
 */
function get_nearest_servers(){
    $DNS_servers = [];
    $DNS_servers_z = file_get_contents('https://api.opennicproject.org/geoip/?jsonp&res=4&ip=&nearest');
    preg_match_all( '/(\{[^}]+\})/', $DNS_servers_z, $DNS_servers_JSON);
    foreach( $DNS_servers_JSON[1] as $DNS_server){
        $d = json_decode( $DNS_server, true);
        $DNS_servers[] = $d['ip'];
    }
    return $DNS_servers;
}

/*
 *
    public $dns =>
    array(6) {
      [0] =>
      string(11) "37.187.0.40"
      [1] =>
      string(12) "87.98.175.85"
      [2] =>
      string(14) "193.183.98.154"
      [3] =>
      string(7) "8.8.8.8"
      [4] =>
      string(13) "192.168.0.254"
      [5] =>
      string(0) ""
    }

 */