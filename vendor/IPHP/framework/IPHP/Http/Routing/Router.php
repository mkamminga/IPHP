<?php
namespace IPHP\Http\Routing;
use IPHP\Http\Request;
use IPHP\Http\Routing\Exceptions\InvalidRouteConfigException;

class Router {
	private $registerdRoutes = [];
	private $defaultNamedGroups = [];
	private $filters = [];
	private $exceptionRoute;
	private $request;
	private $routeMatch;
	private $registeredNamedRoutes = [];
	/**
	 * [__construct description]
	 * @param array $routingConfig [description]
	 */
	public function __construct (array $routingConfig) {
		$this->request = new Request;
		//throw exception if config is invalid
		if ($this->isValidConfig($routingConfig)){
			$this->settingsFromConfig($routingConfig['settings']);
			$this->registerdRoutes = $routingConfig['routes'];
		}
	}
	/**
	 * [isValidConfig description]
	 * @param  array   $config [description]
	 * @return boolean         [description]
	 */
	private function isValidConfig(array $config){
		$validateAgainst = [
			'settings' => [
				'defaults',
				'exceptions' => [
					404,
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
	/**
	 * [settingsFromConfig description]
	 * @param  array  $settings [description]
	 * @return [type]           [description]
	 */
	private function settingsFromConfig (array $settings) {
		$this->defaultNamedGroups = $settings['defaults'];
		$this->exceptionRoute = $settings['exceptions']['404'];
	}
	/**
	 * [getNamedGroups description]
	 * @return [type] [description]
	 */
	public function getNamedGroups () {
		return $this->defaultNamedGroups;
	}
	/**
	 * attempts to 
	 * @return [type] [description]
	 */
	public function findMatch () {
		$url 	= $this->request->currentUrl();
		$method = $this->request->getMethod();

		foreach ($this->registerdRoutes as $routeable) {
			if ($routeable instanceof AbstractRoute) {
				$match = $routeable->match($url, $this->defaultNamedGroups, $this, $method);

				if ($match) {
					$this->routeMatch = $match;

					return $match;
				}
			}
		}
		//404 exception
		return new RouteMatch($url, [], $this->exceptionRoute);
	}
	/**
	 * [getRouteMatch description]
	 * @return [type] [description]
	 */
	public function getRouteMatch () {
		return $this->routeMatch;
	}
	/**
	 * 
	 * @param  string $name  [description]
	 * @param  Route  $route [description]
	 * @return [type]        [description]
	 */
	public function registerRoute (Route $route) {
		$this->registeredNamedRoutes[$route->getName()] = $route;
	}
	/**
	 * @todo 
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function urlByRouteName ($name, array $params = []): string {
		$match = NULL;
		if (array_key_exists($name, $this->registeredNamedRoutes)) {
			$match =  $this->registeredNamedRoutes[$name];
		} else {
			foreach ($this->registerdRoutes as $routeable) {
				if ($routeable instanceof AbstractRoute) {
					$match = $routeable->findMatchByName($name, $this);

					if ($match) {
						break;
					}
				}
			}
		}

		if ($match !== NULL && $match instanceof Route) {
			return $match->getUrlFromParams($params, $this->defaultNamedGroups);
		} else {
			throw new \Exception("Route not registerd!");
		}
	}
	/**
	 * Register middleware filters to be applied before every request
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function registerFilter ($filter, array $params = []) {
		$this->filters[] = [$filter => $params];
	}
	/**
	 * [getFilters description]
	 * @return [type] [description]
	 */
	public function getFilters ():array {

		return $this->filters;
	}
	/**
	 * [getRequest description]
	 * @return [type] [description]
	 */
	public function getRequest (): Request {
		return $this->request;
	}
}