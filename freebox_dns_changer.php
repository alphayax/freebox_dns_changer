<?php
namespace alphayax;
use alphayax\utils\cli\GetOpt;
use alphayax\utils\cli\IO;

/**
 * Freebox DNS Auto updater
 * @author <alphayax@gmail.com>
 */

require_once __DIR__ . '/vendor/autoload.php';

$Args = new GetOpt();
$Args->addShortOpt( 'v', 'Enable verbose mode');
$Args->addLongOpt( 'verbose'    , 'Enable verbose mode');
$Args->addLongOpt( 'checkdns'   , 'Check current DNS status');
$Args->addLongOpt( 'udpatedns'  , 'Update DNS servers');
$Args->parse();

$isVerbose = $Args->hasOption( 'v') || $Args->hasOption( 'verbose');

/// Launch app
$App = new freebox\DNS_changer( $isVerbose);

// Check status
if( $Args->hasOption('checkdns')){
    if( ! $App->checkDNS()){
        if( $isVerbose){
            IO::stdout( 'DNS seems to be corrupted. Forced update...', 0, true, IO::COLOR_MAGENTA);
        }
        $App->updateDNS();
    }
}

// Force update
if( $Args->hasOption('updatedns')){
    $App->updateDNS();
}

// No args given
if( ! $Args->hasOption('updatedns') && ! $Args->hasOption('checkdns')){
    IO::stdout( 'Please use the --checkdns or --updatedns flag');
}

