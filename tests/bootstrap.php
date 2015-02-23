<?php

// include deps
include __DIR__ . '/../vendor/autoload.php';


// create temporary directory
define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0777, TRUE);
@mkdir(TEMP_DIR . '/../changelog' , 0777, TRUE);
Tracy\Debugger::$logDirectory = TEMP_DIR;


// cleanup after
register_shutdown_function(function() {
	Nette\Utils\FileSystem::delete(__DIR__ . '/temp');
});
