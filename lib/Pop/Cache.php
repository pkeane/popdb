<?php

class Pop_Cache_Exception extends Exception {
}

class Pop_Cache
{
	private function __construct() {}

	public static function get($config,$ttl=10)
	{
		$class_name = 'Pop_Cache_'.ucfirst($config->getCacheType());
		if (class_exists($class_name)) {
			return new $class_name($config,$ttl);
		} else {
			throw new Pop_Cache_Exception("Error: $class_name is not a valid class!");
		}
	}

	//must be overridden:
	public function expire($cache_id) {}
	public function getData($cache_id,$ttl) {}
	public function expunge() {}
	public function setData($cache_id,$data) {}
}


