<?php
require_once APP_ROOT.'/model/Config.php';
require_once __DIR__.'/S3.php';
require_once __DIR__.'/HDD.php';

class Storage
{
	private static $class_name;

	protected function __construct()
	{
	}

	public static function uploadData($key,$data,$type,$acl='private')
	{
		return call_user_func_array(array(self::getClassName(), __FUNCTION__), func_get_args());
	}

	public static function uploadFile($key,$filename,$type,$acl='private')
	{
		return call_user_func_array(array(self::getClassName(), __FUNCTION__), func_get_args());
	}

	public static function rename($srckey,$dstkey,$acl='private')
	{
		return call_user_func_array(array(self::getClassName(), __FUNCTION__), func_get_args());
	}

	public static function delete($key)
	{
		return call_user_func_array(array(self::getClassName(), __FUNCTION__), func_get_args());
	}

	public static function url($key,$expires=null)
	{
		return call_user_func_array(array(self::getClassName(), __FUNCTION__), func_get_args());
	}
	
	private static function getClassName()
	{
		if(! ( self::$class_name || (self::$class_name = Config::get('storage')) ) ){
			throw new Exception('`storage` config is needed.');
		}
		return self::$class_name;
	}
}
