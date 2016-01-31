<?php
namespace alphayax\freebox\api\v3\login;
use alphayax\freebox\api\v3\freebox_service;
use alphayax\freebox\DNS_changer;
use alphayax\utils\Cli;

/**
 * Class Authorize
 * @package alphayax\freebox\api\v3
 * @author <alphayax@gmail.com>
 */
class Authorize extends freebox_service {

    /// APIs services
    const API_LOGIN_AUTHORIZE     = '/api/v3/login/authorize/';

    /// Authorization status
    const STATUS_UNKNOWN = 'unknown';
    const STATUS_GRANTED = 'granted';
    const STATUS_PENDING = 'pending';
    const STATUS_DENIED  = 'denied';
    const STATUS_TIMEOUT = 'timeout';

    /** @var string */
    private $app_token = '';

    /** @var int */
    private $track_id = 0;

    /** @var string */
    private $status = self::STATUS_UNKNOWN;

    /** @var string */
    private $challenge = '';

    /**
     * Authorize constructor.
     */
    public function __construct(){
        if( file_exists( 'app_token')){
            $this->app_token = file_get_contents( 'app_token');
        }
        else {
            $this->ask_authorization();
            while( $this->status == self::STATUS_PENDING){
                $this->get_authorization_status();
                if( $this->status == self::STATUS_GRANTED){
                    file_put_contents('app_token', $this->app_token); // Save app token
                    break;
                }
                sleep( 10);
            }

            /// For verbose
            switch( $this->status){
                case self::STATUS_GRANTED : Cli::stdout('Access granted !', 0, true, Cli::COLOR_GREEN); break;
                case self::STATUS_TIMEOUT : Cli::stdout('Access denied. You take to long to authorize app.', 0, true, Cli::COLOR_RED); break;
                case self::STATUS_DENIED  : Cli::stdout('Access denied. Freebox denied app connexion', 0, true, Cli::COLOR_RED); break;
            }
        }
    }


    /**
     * Contact the freebox and ask for App auth
     * @throws \Exception
     */
    public function ask_authorization(){
        $rest = $this->getService( self::API_LOGIN_AUTHORIZE);
        $rest->POST([
            'app_id'        => DNS_changer::APP_ID,
            'app_name'      => DNS_changer::APP_NAME,
            'app_version'   => DNS_changer::APP_VERSION,
            'device_name'   => gethostname(),
        ]);

        $response = $rest->getCurlResponse();
        if( ! $response->success) {
            throw new \Exception('Authorize fail. Unable to contact the freebox');
        }

        Cli::stdout( 'Authorization send to Freebox. Waiting for response...', 0, true, Cli::COLOR_YELLOW);

        $this->app_token = $response->result->app_token;
        $this->track_id  = $response->result->track_id;
    }

    /**
     * @throws \Exception
     */
    public function get_authorization_status(){
        Cli::stdout( 'Check authorization status... ', 0, false, Cli::COLOR_YELLOW);

        $rest = $this->getService( self::API_LOGIN_AUTHORIZE . $this->track_id);
        $rest->GET();

        $response = $rest->getCurlResponse();
        if( ! $response->success) {
            throw new \Exception(__FUNCTION__ . ' Fail');
        }

        $this->status = $response->result->status;
        $this->challenge = $response->result->challenge;

        Cli::stdout( $this->status, 0, true, Cli::COLOR_BLUE);
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
