<?php

// include dependencies
include __DIR__ . '/../vendor/autoload.php';


// create temporary directory
define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0777, TRUE);
@mkdir(TEMP_DIR . '/../changelog' , 0777, TRUE);
Tracy\Debugger::$logDirectory = TEMP_DIR;
