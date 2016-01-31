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
$opennic_DNS_servers = opennic\DNS_server::get_nearest_servers();
$google_DNS_servers  = google\DNS_server::get_nearest_servers();
$new_DNS_servers = array_merge( $opennic_DNS_servers, $google_DNS_servers);

/// Update Configuration
$Config = new freebox\api\v3\DHCP( $Login->getSessionToken());
$Config->get_current_configuration();
$Config->set_attribute_configuration(['dns' => $new_DNS_servers]);
$Config->get_current_configuration();
