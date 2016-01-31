<?php
namespace alphayax;

/**
 * Freebox DNS Auto updater
 * @author <alphayax@gmail.com>
 */

/// Autoloader
require_once 'autoload.php';
AYX_Autoloader::Register();

/// Launch app
new freebox\DNS_changer();
