<?php
namespace alphayax\utils;


//$token = 'LttDmpvaWba2lYf9/xuF1YuwiU4aQplagl66odYcArt5mHpG6S/idiGcZo/EiPKE'
//$track_id = 0;



class Rest {

    /** @var resource */
    private $_curl_handler;

    private $_curl_response;


    public function __construct( $_url){
        $this->_curl_handler = curl_init( $_url);
    }

    public function GET( $isJson = true){
        curl_setopt( $this->_curl_handler, CURLOPT_RETURNTRANSFER, true);
        $this->exec( $isJson);
    }

    public function POST( $curl_post_data, $isJson = true){
        curl_setopt( $this->_curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $this->_curl_handler, CURLOPT_POST, true);
        curl_setopt( $this->_curl_handler, CURLOPT_POSTFIELDS, json_encode( $curl_post_data));
        $this->exec( $isJson);
    }

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

    public function getCurlResponse(){
        return $this->_curl_response;
    }

}