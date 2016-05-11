<?php
namespace IPHP\Events;

use IPHP\App\App;

class EventManager {
	private $app;
	private $events = [];

	public function __construct (App $app) {
		$this->app = $app;
	}

	public function register ($event, $listener, $stop = false) {
		if (!isset($this->events[$event])) {
			$this->events[$event] = [];
		}

		$this->events[$event][] = [
			'listener' => $listener,
			'stop' => $stop
		];
	}

	public function registerMass (array $events) {
		$this->events = array_merge_recursive($this->events, $events);
	}

	public function unregister ($event, $listener) {
		if ($this->hasListeners($event)) {
			$i = 0;
			foreach ($this->events[$event] as $handler) {
				
				if ($handler['listener'] == $listener){
					unset($this->events[$event][$i]);
					return;
				}
				$i++;
			}
		}
	}

	public function hasListeners ($event) {
		return isset($this->events[$event]) && !empty($this->events[$event]);
	}

	public function publish ($event, $data = NULL) {
		if ($this->hasListeners($event)) {

			foreach ($this->events[$event] as $handler) {

				$handler['listener']($this->app);
				if ($handler['stop']) {
					break;
				}
			}
		}
	}
}