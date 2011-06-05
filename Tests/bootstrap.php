<?php

require_once __dir__ .'/../BayesPHP/Autoloader.php';

$autoloader = new BayesPHP\Autoloader('BayesPHP', __dir__ . '/../');
$autoloader->register();

require_once 'Mockery/Loader.php';
//require_once 'Hamcrest/hamcrest.php';

$loader = new \Mockery\Loader;
$loader->register();

?>
