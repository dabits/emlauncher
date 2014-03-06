<?php
require_once APP_ROOT.'/model/Config.php';
require_once __DIR__.'/S3.php';
require_once __DIR__.'/HDD.php';

class Storage {

	protected function __construct()
	{
	}

	public static function uploadData($key,$data,$type,$acl='private')
	{
		$m = Config::get('storage');
		return $m::uploadData($key,$data,$type,$acl);
	}

	public static function uploadFile($key,$filename,$type,$acl='private')
	{
		$m = Config::get('storage');
		return $m::uploadFile($key,$filename,$type,$acl);
	}

	public static function rename($srckey,$dstkey,$acl='private')
	{
		$m = Config::get('storage');
		return $m::rename($srckey,$dstkey,$acl);
	}

	public static function delete($key)
	{
		$m = Config::get('storage');
		return $m::delete($key);
	}

	public static function url($key,$expires=null)
	{
		$m = Config::get('storage');
		return $m::url($key,$expires);
	}
}
