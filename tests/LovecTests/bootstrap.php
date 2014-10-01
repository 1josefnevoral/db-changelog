<?php

if (@!include __DIR__ . '/../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

// create temporary directory
define('TEMP_DIR', __DIR__ . '/../tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);
Tracy\Debugger::$logDirectory = TEMP_DIR;


$_SERVER = array_intersect_key($_SERVER, array_flip(array(
	'PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv')));
$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = array();

$_SERVER['SCRIPT_FILENAME'] = __FILE__; // to make %wwwDir% work


function run(Tester\TestCase $testCase) {
	$testCase->run();
}


$config = new Nette\Configurator;
$config->setTempDirectory(TEMP_DIR);
$config->addConfig(__DIR__ . '/config.neon');
$config->createRobotLoader()
	->addDirectory(__DIR__ . '/../../src')
	->addDirectory(__DIR__)
	->register();

return $config->createContainer();
