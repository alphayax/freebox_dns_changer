<?php
namespace alphayax\freebox\api\v3;

/**
 * Class Authorize
 * @package alphayax\freebox\api\v3
 */
class Authorize {

    const APP_ID = 'com.alphayax.freebox.dns_changer';
    const APP_NAME = 'Freebox DNS changer';
    const APP_VERSION = '0.0.2';

    const STATUS_GRANTED = 'granted';

    /** @var string */
    private $app_token = '';

    /** @var int */
    private $track_id = 0;

    /** @var string */
    private $status = '';

    /** @var string */
    private $challenge = '';

    public function __construct(){
        if( file_exists( 'app_token')){
            $this->app_token = file_get_contents( 'app_token');
        }
        else {
            $this->ask_authorization();
            $is_authorized = false;
            for( $i = 0; $i < 4; $i++){
                $this->get_authorization_status();
                if( $this->getStatus() == self::STATUS_GRANTED){
                    $is_authorized = true;
                    break;
                }
                sleep( 10);
            }

            if( $is_authorized){

                /// Save app token
                file_put_contents('app_token', $this->app_token);

                echo "Access granted !\n";
            }
        }
    }


    /**
     * Contact the freebox and ask for App auth
     * @throws \Exception
     */
    public function ask_authorization()
    {

        $service = '/api/v3/login/authorize/';
        $host = 'mafreebox.freebox.fr';

        $rest = new \alphayax\utils\Rest('http://' . $host . $service);
        $rest->POST([
            'app_id' => self::APP_ID,
            'app_name' => self::APP_NAME,
            'app_version' => self::APP_VERSION,
            'device_name' => gethostname(),
        ]);

        $response = $rest->getCurlResponse();
        if (!$response->success) {
            throw new \Exception('Authorize fail. Unable to contact the freebox');
        }

        echo "Demande d'autorisation envoyée a la freebox\n";

        $this->app_token = $response->result->app_token;
        $this->track_id  = $response->result->track_id;
    }


    public function get_authorization_status()
    {

        $service = '/api/v3/login/authorize/' . $this->track_id;
        $host = 'mafreebox.freebox.fr';

        echo "Verification de la demande d'autorisation... ";
        $rest = new \alphayax\utils\Rest('http://' . $host . $service);
        $rest->GET();

        $response = $rest->getCurlResponse();
        if (!$response->success) {
            throw new \Exception(__FUNCTION__ . ' Fail');
        }

        $this->status = $response->result->status;
        $this->challenge = $response->result->challenge;

        echo $this->status . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @return string
     */
    public function getAppToken(){
        return $this->app_token;
    }

}
