<?php

/**
 * @return array
 */
function get_nearest_servers(){
    $DNS_servers = [];
    $DNS_servers_z = file_get_contents('https://api.opennicproject.org/geoip/?jsonp&res=4&ip=&nearest');
    preg_match_all( '/(\{[^}]+\})/', $DNS_servers_z, $DNS_servers_JSON);
    foreach( $DNS_servers_JSON[1] as $DNS_server){
        $d = json_decode( $DNS_server, true);
        $DNS_servers[] = $d['ip'];
    }
    return $DNS_servers;
}
