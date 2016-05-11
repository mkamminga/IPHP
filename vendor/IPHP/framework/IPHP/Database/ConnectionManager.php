<?php
namespace IPHP\Database;

class ConnectionManager {
	private $connections;
	private $settings;

	public function __construct (array $settings) {
		$this->settings = $settings;
	}

	private function create ($name) {
		$settings = $this->settings[$name];

		$this->connections[$name] = new DB(
			$settings['engine'],
			$settings['host'],
			$settings['dbname'],
			$settings['dbuser'],
			$settings['dbpassword']
		);
	}

	public function set ($name, DB $connection) {
		$this->connections[$name] = $connection;
	}

	public function get ($name) {
		if (isset($this->connections[$name])) {
			return $this->connections[$name];
		} else if (isset($this->settings[$name])) {
			$this->create($name);

			return $this->connections[$name];
		}

		return NULL;
	}

	public function remove ($name) {
		//
	}
}