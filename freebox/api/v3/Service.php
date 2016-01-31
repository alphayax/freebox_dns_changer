<?php
namespace alphayax\freebox\api\v3;

/**
 * Class freebox_service
 */
abstract class Service {

    /// Freebox API host URI
    const API_HOST = 'http://mafreebox.freebox.fr';

    /**
     * @param $service
     * @return \alphayax\utils\Rest
     */
    protected function getService( $service){
        return new \alphayax\utils\Rest( static::API_HOST . $service);
    }

    /**
     * @param $service
     * @return \alphayax\freebox\utils\RestAuth
     */
    protected function getAuthService( $service){
        return new \alphayax\freebox\utils\RestAuth( static::API_HOST . $service);
    }
}
