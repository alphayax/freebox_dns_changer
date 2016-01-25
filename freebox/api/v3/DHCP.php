<?php
namespace alphayax\freebox\api\v3;


/**
 * Class DHCP
 * @package alphayax\freebox\api\v3
 */
class DHCP {

    /** @var string */
    private $session_token  = '';

    public function __construct( $session_token){
        $this->session_token = $session_token;
    }

    public function get_current_configuration(){

        $service = '/api/v3/dhcp/config/';
        $host = 'mafreebox.freebox.fr';

        $rest = new \alphayax\freebox\utils\RestAuth( 'http://' . $host . $service);
        $rest->setSessionToken( $this->session_token);
        $rest->GET();

        $response = $rest->getCurlResponse();
        var_dump( $response);
        if( ! $response->success){
            throw new \Exception( __FUNCTION__ . ' Fail');
        }
    }

    /**
     *
     */
    public function set_attribute_configuration( $configName, $configValue){

        $service = '/api/v3/dhcp/config/';
        $host = 'mafreebox.freebox.fr';

        $rest = new \alphayax\freebox\utils\RestAuth( 'http://' . $host . $service);
        $rest->setSessionToken( $this->session_token);
        $rest->PUT( [
            $configName => $configValue
        ]);

        $response = $rest->getCurlResponse();
        var_dump( $response);
    }

}
