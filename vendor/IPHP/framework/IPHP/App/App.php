<?php
namespace IPHP\App;

use IPHP\App\Kernel\Kernel;
use IPHP\Events\EventManager;
use IPHP\Database\ConnectionManager;
use IPHP\Model\Model;

class App extends ServiceManager{
	private $configs = [];
	private $instances = [];
	private $aliasInstances = [];
	private $registeredServices = [];

	public function __construct (array $configFiles) {
		$this->bootstrap($configFiles);
	}

	private function bootstrap (array $configFiles) {
		$this->resolveConfig($configFiles);
		$this->registerServices();
		$this->registerEvents();

		$this->getService('eventManager')->publish('bootstrapped');
	}

	private function resolveConfig (array $configFiles) {
		foreach ($configFiles as $key => $filePath) {
			if (is_file($filePath)) {
				$value = include $filePath;
				if (is_array($value)) {
					$this->configs[$key] = new Config($value);
				}
			} else {
				throw new \Exception("Config file (". $key .": ". $filePath .") doesn't exist!");
			}
		}
	}

	public function hasConfig ($name) {
		return (array_key_exists($name, $this->configs));
	}

	public function getConfig ($name) {
		if ($this->hasConfig($name)) {
			return $this->configs[$name];
		}
	}

	private function registerServices () {
		if ($this->hasConfig('providers')) {
			$this->services = $this->getConfig('providers');
		}
		
		$this->registerInstanceAlias('eventManager', EventManager::class, new EventManager($this));
		$this->registerInstanceAlias('reflectorService', ReflectorService::class, new ReflectorService($this));
		
		if ($this->hasConfig('app')){
			$app = $this->getConfig('app');
			if ($app->hasKey('database', $app)){
				$this->registerInstanceAlias('connectionManager', ConnectionManager::class, new ConnectionManager($app->getValue('database')));
			}
		}
	}

	private function registerEvents () {
		if ($this->hasConfig('events') && $this->hasService('eventManager')) {
			$eventManager = $this->getService('eventManager');
			$eventManager->registerMass($this->getConfig('events')->data());
			//Register default connection in model
			$eventManager->register('bootstrapped', function (ServiceManager $app) {
				if ($app->hasService('connectionManager')) {
					$connection = $app->getService('connectionManager')->get('default');

					if ($connection != NULL) {
						Model::setDefaultConnection($connection);
					}
				}

				$app->registerInstanceAlias('serviceManager', ServiceManager::class, $app);
			});
		}	
	}

	public function registerService ($name, $service) {
		$this->registeredServices[$name] = $service;
	}

	public function registerInstance ($name, $service) {
		$this->instances[$name] = $service;
	}

	public function registerInstanceAlias ($name, $alias, $service) {
		$this->registerInstance($name, $service);
		$this->aliasInstances[$alias] = $name;
	}

	public function hasService ($name) {
		return array_key_exists($name, $this->instances) 
				|| array_key_exists($name, $this->registeredServices) 
				|| array_key_exists($name, $this->aliasInstances);
	}

	public function getService ($name) {
		if (array_key_exists($name, $this->instances)){
			return $this->instances[$name];
		} else if (array_key_exists($name, $this->aliasInstances)) {
			return $this->instances[ $this->aliasInstances[$name] ];
		} else if (array_key_exists($name, $this->registeredServices)) {
			$this->instances[$name] = $this->registeredServices[$name]($this);
			return $this->instances[$name];
		}
		return NULL;
	}

	public function resolve (Kernel $kernel) {
		$kernel->resolve();
	}
}