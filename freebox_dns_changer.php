<?php
namespace alphayax;

/**
 * Freebox DNS Auto updater
 * @author <alphayax@gmail.com>
 */

/// Autoloader
require_once 'autoload.php';
AYX_Autoloader::Register();

/// Check App Auth from Freebox
$Auth  = new freebox\api\v3\Authorize();

/// Get Session
$Login = new freebox\api\v3\Login( $Auth->getAppToken());
$Login->ask_login_status();
$Login->create_session();

/// Find new DNS servers
$new_DNS_servers = opennic\DNS_server::get_nearest_servers();
$new_DNS_servers = array_merge( $new_DNS_servers, ['8.8.8.8', '192.168.0.254']);

/// Update Configuration
$Config = new freebox\api\v3\DHCP( $Login->getSessionToken());
$Config->get_current_configuration();
$Config->set_attribute_configuration(['dns' => $new_DNS_servers]);
$Config->get_current_configuration();
