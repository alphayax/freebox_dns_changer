<?php
namespace alphayax\freebox\utils;


/**
 * Class Rest
 * @package alphayax\utils
 */
class RestAuth extends \alphayax\utils\Rest {

    /** @var string */
    protected $session_token = '';

    /**
     * @param bool $isJson
     */
    public function GET( $isJson = true){
        $this->add_XFbxAppAuth_Header();
        parent::GET( $isJson);
    }

    /**
     * @param bool|true $isJson
     */
    public function POST( $isJson = true){
        $this->add_XFbxAppAuth_Header();
        parent::POST( $isJson);
    }

    /**
     * @param bool|true $isJson
     */
    public function PUT( $isJson = true){
        $this->add_XFbxAppAuth_Header();
        parent::PUT( $isJson);
    }

    /**
     * Add the session token in the X-Fbx-App-Auth Header
     */
    protected function add_XFbxAppAuth_Header(){
        curl_setopt( $this->_curl_handler, CURLOPT_HTTPHEADER, array(
            'X-Fbx-App-Auth: '. $this->session_token,
        ));
    }

    /**
     * @param $session_token
     */
    public function setSessionToken($session_token){
        $this->session_token = $session_token;
    }
}
