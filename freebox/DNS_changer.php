<?php
namespace alphayax\freebox;
use alphayax\opennic;
use alphayax\google;

/**
 * Class DNS_changer
 * @package alphayax\freebox
 */
class DNS_changer {

    const APP_ID        = 'com.alphayax.freebox.dns_changer';
    const APP_NAME      = 'Freebox DNS changer';
    const APP_VERSION   = '1.0.2';

    const FREEBOX_HOST  = 'mafreebox.freebox.fr';

    /**
     * DNS_changer constructor.
     */
    public function __construct(){

        /// Define our application
        $App = new utils\Application( self::APP_ID, self::APP_NAME, self::APP_VERSION);
        $App->authorize();
        $App->openSession();

        /// Find new DNS servers
        $opennic_DNS_servers = opennic\DNS_server::get_nearest_servers();
        $google_DNS_servers  = google\DNS_server::get_nearest_servers();
        $new_DNS_servers = array_merge( $opennic_DNS_servers, $google_DNS_servers);

        /// Update Configuration
        $Config = new api\v3\dhcp\DHCP( $App);
        $prevConfig = $Config->get_current_configuration();
        $newConfig  = $Config->set_attribute_configuration(['dns' => $new_DNS_servers]);

        /// Trace
        var_dump( $prevConfig, $newConfig, $new_DNS_servers);
    }

}