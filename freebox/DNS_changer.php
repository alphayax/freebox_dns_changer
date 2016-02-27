<?php
namespace alphayax\freebox;
use alphayax\freebox\api\v3 as FreeboxAPI;
use alphayax\opennic;
use alphayax\google;
use alphayax\utils\cli\IO;

/**
 * Class DNS_changer
 * @package alphayax\freebox
 */
class DNS_changer {

    const APP_ID        = 'com.alphayax.freebox.dns_changer';
    const APP_NAME      = 'Freebox DNS changer';
    const APP_VERSION   = '1.0.3';

    const FREEBOX_HOST  = 'mafreebox.freebox.fr';

    /** @var utils\Application Freebox App */
    protected $app;

    /** @var bool Verbose Mode */
    protected $isVerbose;

    /**
     * DNS_changer constructor.
     * @param bool $verbose
     */
    public function __construct( $verbose = false){
        /// Define our application
        $this->app = new utils\Application( self::APP_ID, self::APP_NAME, self::APP_VERSION);
        $this->app->authorize();
        $this->app->openSession();
        /// Saving others parameters
        $this->isVerbose = $verbose;
    }

    /**
     * Check current DNS status
     * @return bool
     */
    public function checkDNS(){
        if( $this->isVerbose){
            IO::stdout( 'Checking DNS Servers...', 0, true, IO::COLOR_MAGENTA);
        }
        $isRespondingDNS = true;
        $Config = new FreeboxAPI\services\config\DHCP( $this->app);
        $currentConfig = $Config->get_current_configuration();
        $a = $currentConfig->dns;
        foreach( $a as $dnsserver){
            $output = null;
            $exit   = null;
            if( empty( $dnsserver)){
                if( $this->isVerbose){
                    IO::stdout( "Server not defined. Skip", 1, true, IO::COLOR_YELLOW);
                }
                continue;
            }
            exec( 'nslookup -timeout=1 t411.io '. $dnsserver, $output, $exit);
            if( empty( $exit)){
                if( $this->isVerbose){
                    IO::stdout( "Server $dnsserver is OK", 1, true, IO::COLOR_GREEN);
                }
            } else {
                if( $this->isVerbose){
                    IO::stdout( "Server $dnsserver is OK", 1, true, IO::COLOR_RED);
                }
                $isRespondingDNS = false;
            }
        }
        return $isRespondingDNS && ! empty( $a);
    }

    /**
     * Update DNS
     * Get new DNS servers from openDNS and Google
     */
    public function updateDNS(){
        if( $this->isVerbose){
            IO::stdout( 'Updating DNS Servers...', 0, true, IO::COLOR_MAGENTA);
        }
        /// Find new DNS servers
        $opennic_DNS_servers = opennic\DNS_server::get_nearest_servers();
        $google_DNS_servers  = google\DNS_server::get_nearest_servers();
        $new_DNS_servers = array_merge( $opennic_DNS_servers, $google_DNS_servers);

        /// Update Configuration
        $Config = new FreeboxAPI\services\config\DHCP( $this->app);
        $Config->set_attribute_configuration(['dns' => $new_DNS_servers]);
        $newConfig = $Config->get_current_configuration();

        if( $this->isVerbose){
            $a = $newConfig->dns;
            IO::stdout( 'New DNS Servers :', 0, true, IO::COLOR_MAGENTA);
            foreach( $a as $ServerDNS){
                IO::stdout( "- ". ($ServerDNS ?: 'Undefined'), 1);
            }
        }
    }

}
