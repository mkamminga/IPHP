<?php
namespace IPHP\App;

abstract class ServiceManager {
	public abstract function hasService ($name);
	public abstract function getService ($name);
}