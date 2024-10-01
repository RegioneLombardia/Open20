<?php
error_reporting(0);
require(__DIR__ . '/vendor/autoload.php');

$config = require(__DIR__ . '/frontend/configs/env.php');

echo json_encode($config);