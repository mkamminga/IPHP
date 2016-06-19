<?php
namespace IPHP\App;

abstract class ServiceManager {
	public abstract function hasService ($name);
	public abstract function getService ($name);
	public abstract function hasConfig ($name);
	public abstract function getConfig ($name);
}