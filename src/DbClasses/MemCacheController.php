<?php

declare(strict_types=1);

namespace App\DbClasses;

/**
 * This is the memcache factory that checks if any controller is in cache
 * it will return data from cache else it will call the controller
 **/
class MemCacheController
{
	public function __construct()
	{
		try{
			if (class_exists('Memcached')) {
				$mc = new Memcached(); 
				//connect
				if($mc->addServer('localhost', '11211')){
					return $mc;
				}
			}
			elseif(class_exists('Memcache')) {
				$mc = new Memcache; 
				if($mc->connect('localhost', '11211')){
					return $mc;
				}
			}
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}
}
