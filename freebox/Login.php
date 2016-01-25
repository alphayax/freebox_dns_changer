<?php
namespace alphayax\freebox;



class Login {

    const APP_ID        = 'com.alphayax.freebox.dns_changer';
    const APP_NAME      = 'Freebox DNS changer';
    const APP_VERSION   = '0.0.2';

    /** @var string */
    private $app_token  = '';


    /** @var string */
    private $challenge  = '';

    private $password_salt  = '';

    private $logged_in  = false;


    private $session_token;


    public function __construct( $app_token){
        $this->app_token = $app_token;
    }

    public function ask_login_status(){

        $service = '/api/v3/login/';
        $host = 'mafreebox.freebox.fr';

        $rest = new \alphayax\utils\Rest( 'http://' . $host . $service);
        $rest->GET();

        $response = $rest->getCurlResponse();
        if( ! $response->success){
            throw new \Exception( __FUNCTION__ . ' Fail');
        }


        $this->logged_in = $response->result->logged_in;
        $this->challenge = $response->result->challenge;
        $this->password_salt = $response->result->password_salt;

        var_dump( $response);
    }


    public function create_session(){

        $service = '/api/v3/login/session/';
        $host = 'mafreebox.freebox.fr';

        $rest = new \alphayax\utils\Rest( 'http://' . $host . $service);
        $rest->POST([
            'app_id'    => self::APP_ID,
            'password'  => hash_hmac( 'sha1', $this->challenge, $this->app_token),
        ]);

        $a= hash_hmac( 'sha1', $this->app_token, $this->challenge);
        var_dump( $a, $this->challenge, $this->app_token);

        $response = $rest->getCurlResponse();
        if( ! $response->success){
            echo "ERROR CODE : ". $response->error_code;
            echo "MESSAGE : ". $response->msg;
            throw new \Exception( __FUNCTION__ . ' Fail');
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
