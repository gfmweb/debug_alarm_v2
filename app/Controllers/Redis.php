<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Redis extends BaseController
{
	public static $Rediska = null;
	
    public static function initial()
    {
       self::$Rediska = new \Redis();
	   self::$Rediska->connect('127.0.0.1',6379);
    }
	
	public static function getInstance():object
	{
		if(is_null (self::$Rediska)){
			self::initial();
		}
		return self::$Rediska;
	}
	
	public static function set(string $key, string $value):bool
	{
		
		self::$Rediska->set($key);
		return true;
	}
	
	
}
