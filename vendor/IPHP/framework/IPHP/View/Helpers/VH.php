<?php
namespace IPHP\View\Helpers;

use IPHP\App\ReflectorService;

class VH {
	private static $reflectorService = NULL;
	private static $helperMap = [
		'url' => Url::class,
		'form' => Form::class
	];

	public static function register (ReflectorService $rs) {
		self::$reflectorService = $rs;
	}

	public static function service(string $name = '') {
		if (!self::$reflectorService) {
			throw new \Exception("Not registerd!");
		}

		if (array_key_exists($name, self::$helperMap)) {
			return self::$reflectorService->createInstance(self::$helperMap[$name]);
		} else {
			throw new \Exception("Service not found");
		}
	}
}