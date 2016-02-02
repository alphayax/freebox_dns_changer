<?php
namespace alphayax;

/**
 * Freebox DNS Auto updater
 * @author <alphayax@gmail.com>
 */
require_once __DIR__ . '/vendor/autoload.php';

/// Launch app
new freebox\DNS_changer();
