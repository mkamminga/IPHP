<?php
namespace IPHP\Http\Routing;
use IPHP\Http\Request;
use IPHP\Http\Routing\Exceptions\InvalidRouteConfigException;

class Router {
	private $registerdRoutes = [];
	private $defaultNamedGroups = [];
	private $filters = [];
	private $request;
	public function __construct (array $routingConfig) {
		$this->request = new Request;
		//throw exception if config is invalid
		if ($this->isValidConfig($routingConfig)){
			$this->settingsFromConfig($routingConfig['settings']);
			$this->registerdRoutes = $routingConfig['routes'];
		}
	}

	private function isValidConfig(array $config){
		$validateAgainst = [
			'settings' => [
				'defaults',
				'exceptions' => [
					404,
					403
				]
			],
			'routes'
		];

		$hasConfigKey = function (array $configSettings, array $input) use (&$hasConfigKey) {

			foreach ($configSettings as $key => $value) {
				
				if (!is_array($value)) {
					$key = $value;
				}

				if (array_key_exists($key, $input)) {
					if (is_array($value)) {
						if (is_array($input[$key])){
							$hasConfigKey($configSettings[$key], $input[$key]);
						} else {
							throw new InvalidRouteConfigException("Config setting of key '". $key ."' must be an array!");
						}
					}
				} else {
					throw new InvalidRouteConfigException("Missing config key: '". $key ."'!");
				}
			}
		};

		$hasConfigKey($validateAgainst, $config);


		return true;
	}

	private function settingsFromConfig (array $settings) {
		$this->defaultNamedGroups = $settings['defaults'];
	}
	/**
	 * attempts to 
	 * @return [type] [description]
	 */
	public function findMatch () {
		$url = $this->request->currentUrl();

		foreach ($this->registerdRoutes as $routeable) {
			if ($routeable instanceof AbstractRoute) {
				$match = $routeable->match($url, $this->defaultNamedGroups, $this);

				if ($match) {
					return $match;
				}
			}
		}

		return NULL;
	}
	/**
	 * @todo 
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function findMatchByName ($name) {
		//
	}
	/**
	 * Register middleware filters to be applied before every request
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function registerFilter ($filter, array $params = []) {
		$this->filters[] = [$filter => $params];
	}

	public function getFilters ():array {
		return $this->filters;
	}

	public function getRequest (): Request {
		return $this->request;
	}
}