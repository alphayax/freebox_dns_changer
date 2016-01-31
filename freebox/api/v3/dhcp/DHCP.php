<?php
namespace alphayax\freebox\api\v3\dhcp;
use alphayax\freebox\api\v3\freebox_service;


/**
 * Class DHCP
 * @package alphayax\freebox\api\v3
 * @author <alphayax@gmail.com>
 */
class DHCP extends freebox_service {

    const API_DHCP_CONFIG = '/api/v3/dhcp/config/';

    /** @var string */
    private $session_token  = '';

    /**
     * DHCP constructor.
     * @param $session_token
     */
    public function __construct( $session_token){
        $this->session_token = $session_token;
    }

    /**
     * @throws \Exception
     */
    public function get_current_configuration(){
        $rest = $this->getAuthService( self::API_DHCP_CONFIG);
        $rest->setSessionToken( $this->session_token);
        $rest->GET();

        $response = $rest->getCurlResponse();
        var_dump( $response);
        if( ! $response->success){
            throw new \Exception( __FUNCTION__ . ' Fail');
        }
    }

    /**
     * @param array $new_config_x
     */
    public function set_attribute_configuration( $new_config_x = []){
        $rest = $this->getAuthService( self::API_DHCP_CONFIG);
        $rest->setSessionToken( $this->session_token);
        $rest->PUT( $new_config_x);

        $response = $rest->getCurlResponse();
        var_dump( $response);
    }

}
