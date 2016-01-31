<?php
namespace alphayax\google;

/**
 * Class DNS_server
 * @package alphayax\google
 * @author <alphayax@gmail.com>
 */
class DNS_server {

    /**
     * @return array
     */
    public static function get_nearest_servers(){
        return [
            '8.8.8.8',
            '8.8.4.4'
        ];
    }

}
