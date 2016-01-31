<?php
namespace alphayax\freebox\api\v3\login;
use alphayax\freebox\api\v3\freebox_service;
use alphayax\freebox\DNS_changer;


/**
 * Class Login
 * @package alphayax\freebox\api\v3
 * @author <alphayax@gmail.com>
 */
class Login extends freebox_service {

    /// APIs services
    const API_LOGIN             = '/api/v3/login/';
    const API_LOGIN_SESSION     = '/api/v3/login/session/';

    /** @var string */
    private $app_token  = '';

    /** @var string */
    private $challenge  = '';

    private $password_salt  = '';

    private $logged_in  = false;


    private $session_token;

    /**
     * Login constructor.
     * @param $app_token
     */
    public function __construct( $app_token){
        $this->app_token = $app_token;
    }

    /**
     * @throws \Exception
     */
    public function ask_login_status(){
        $rest = $this->getService( static::API_LOGIN);
        $rest->GET();

        $response = $rest->getCurlResponse();
        if( ! $response->success){
            throw new \Exception( __FUNCTION__ . ' Fail');
        }

        $this->logged_in = $response->result->logged_in;
        $this->challenge = $response->result->challenge;
        $this->password_salt = $response->result->password_salt;
    }

    /**
     * @throws \Exception
     */
    public function create_session(){
        $rest = $this->getService( static::API_LOGIN_SESSION);
        $rest->POST([
            'app_id'    => DNS_changer::APP_ID,
            'password'  => hash_hmac( 'sha1', $this->challenge, $this->app_token),
        ]);

        $response = $rest->getCurlResponse();
        if( ! $response->success){
            throw new \Exception( @$response->msg . ' '. @$response->error_code);
        }

        $this->session_token = $response->result->session_token;
    }

    /**
     * @return mixed
     */
    public function getSessionToken(){
        return $this->session_token;
    }

}
