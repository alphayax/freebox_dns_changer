<?php
namespace alphayax\utils;

/**
 * Class Rest
 * @package alphayax\utils
 */
class Rest {

    /** @var resource */
    protected $_curl_handler;

    /** @var array */
    protected $_curl_response;

    /**
     * Rest constructor.
     * @param $_url
     */
    public function __construct( $_url){
        $this->_curl_handler = curl_init( $_url);
    }

    /**
     * @param bool $isJson
     */
    public function GET( $isJson = true){
        curl_setopt( $this->_curl_handler, CURLOPT_RETURNTRANSFER, true);
        $this->exec( $isJson);
    }

    /**
     * @param      $curl_post_data
     * @param bool $isJson
     */
    public function POST( $curl_post_data, $isJson = true){
        curl_setopt( $this->_curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $this->_curl_handler, CURLOPT_POST, true);
        curl_setopt( $this->_curl_handler, CURLOPT_POSTFIELDS, json_encode( $curl_post_data));
        $this->exec( $isJson);
    }

    /**
     * @param $curl_post_data
     * @param bool|true $isJson
     */
    public function PUT( $curl_post_data, $isJson = true){
        curl_setopt( $this->_curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $this->_curl_handler, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt( $this->_curl_handler, CURLOPT_POSTFIELDS, json_encode( $curl_post_data));
        $this->exec( $isJson);
    }


    /**
     * @param $isJson
     */
    private function exec( $isJson){
        $this->_curl_response = curl_exec( $this->_curl_handler);
        if( $this->_curl_response === false) {
            $info = curl_getinfo( $this->_curl_handler);
            curl_close( $this->_curl_handler);
            die('error occurred during curl exec. Additional info: ' . var_export($info));
        }
        curl_close( $this->_curl_handler);

        // Decode JSON if we need to
        if( $isJson){
            $this->_curl_response = json_decode( $this->_curl_response);
        }
        $this->_curl_response;
    }

    /**
     * @return mixed
     */
    public function getCurlResponse(){
        return $this->_curl_response;
    }

}
