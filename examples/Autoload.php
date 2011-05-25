<?php
require_once '../BayesPHP/Autoloader.php';

$autoloader = new BayesPHP\Autoloader('BayesPHP', __DIR__ . '/..');
$autoloader->register();

new \BayesPHP\WordCounter();

?>
