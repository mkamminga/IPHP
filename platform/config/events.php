<?php
use IPHP\App\ServiceManager;
/**
 * Providers
 */
return [
	'route.dispatch' => [[
		'stop' => false,
		'listener' => function (ServiceManager $sm) {
		}
	]]
];