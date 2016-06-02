<?php
namespace IPHP\App\Kernel;

use IPHP\App\App;
use IPHP\App\ServiceManager;
use IPHP\Http\Response;
use IPHP\Http\Request;
use IPHP\Http\Routing\Router;
use IPHP\Http\Routing\RouteMatch;
use IPHP\View\Compiler\Compiler;
use IPHP\View\Response as ViewResponse;

class HttpKernel extends Kernel {
	private $router;
	private $eventManager;

	public function __construct (ServiceManager $app) {
		parent::__construct($app);

		if ($this->app->hasConfig('routes')) {
			//throws invalid config exception
			$this->router = new Router($this->app->getConfig('routes')->data());
			$this->eventManager = $app->getService('eventManager');

			$app->registerInstanceAlias('router', Router::class, $this->router);
			$app->registerInstanceAlias('request', Request::class, $this->router->getRequest());

			$this->app->getService('eventManager')->publish('kernel.booted');

		} else {
			throw new \Exception("Missing config file: Routes");
		}
	}

	public function resolve(){
		$match = $this->router->findMatch();
		
		if ($match) {
			$this->app->getService('eventManager')->publish('route.match');
			$this->handleRouteMatch($match);
		} else {
			throw new RouteNotFoundException("Could not resolve any route!");
		}
	}

	public function handleRouteMatch (RouteMatch $routeMatch) {
		try {
			//Apply filters first, now that a route match has been found
			$this->applyRouteFilters();
			//Now continue and attempt to create the controller 
			$route = $routeMatch->getRoute();
			$instanciableController = $route->getController();

			$reflectionService = $this->app->getService('reflectorService');
			//Register namedvars, such as $id, or $title, from the the route match so the controller can type hint those in the matched method call
			$controller = $reflectionService->createInstance($instanciableController);
			$method = $route->getControllerMethod();
			$viewResponse = $reflectionService->callMethod($controller, $method, $routeMatch->getParams());

			if ($viewResponse && $viewResponse instanceof ViewResponse) {
				$this->app->registerInstanceAlias('viewResponse', ViewResponse::class, $viewResponse);
				$this->app->getService('eventManager')->publish('route.dispatch');
				$this->finnish($viewResponse);
			}			
		} catch (\Exception $e) {
			print($e->getMessage());
			exit;
		}
	}

	public function applyRouteFilters () {

		$filters = $this->router->getFilters();
	
		if (!empty($filters)) {
			$reflectionService = $this->app->getService('reflectorService');
			foreach ($filters as $filter) {
				list($instanciable, $params) = each($filter);
				$filterInstance = $reflectionService->createInstance($instanciable, $params);
				$reflectionService->callMethod($filterInstance, 'handle');
			}
		}
	}

	public function finnish (ViewResponse $viewResponse) {
		$reflectionService = $this->app->getService('reflectorService');
		$strategyClass = $viewResponse->getStrategy();
		//throws exception on failure
		$strategy = $reflectionService->createInstance($strategyClass);
		$strategy->resolve($viewResponse);
		
		$this->app->getService('eventManager')->publish('finnish');
	}
}