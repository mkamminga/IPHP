<?php
declare(strict_types=1);
//Set the autoloader that registers all vendors
$ds = DIRECTORY_SEPARATOR;
require_once ('..'. $ds .'vendor'. $ds .'autoloader'. $ds .'autoload.php');

$configPath = '..'. $ds .'platform'. $ds .'config' . $ds;

$app = new IPHP\App\App([
	'app' => $configPath . 'app.php',
	'routes' => $configPath . 'routes.php',
	'providers' => $configPath . 'providers.php',
	'events' => $configPath . 'events.php',
]);

$httpKernel = new IPHP\App\Kernel\HttpKernel($app);

$app->resolve($httpKernel);