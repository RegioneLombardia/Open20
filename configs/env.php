<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
$finalConfig = require_once( './frontend/configs/env.php');

unset($finalConfig['components']['request']);
unset($finalConfig['components']['translatemanager']);
unset($finalConfig['components']['session']);
unset($finalConfig['components']['errorHandler']);
unset($finalConfig['modules']['favorites']);
unset($finalConfig['modules']['socialauth']);
if (($key = array_search('socialauth', $finalConfig['bootstrap'])) !== false) {
    unset($finalConfig['bootstrap'][$key]);
}

return $finalConfig;