<?php
require_once APP_ROOT.'/model/Config.php';

class HDD {

	protected function __construct()
	{
		$config = Config::get('hdd');
	}

	public static function uploadData($key,$data,$type,$acl='private')
	{
		$config = Config::get('hdd');

		switch(get_class($data))
		{
		case 'Imagick':
			$filename = $config['uploadpath'].$key;
			if (!file_exists(dirname($filename)))
				mkdir(dirname($filename), 0755, true);
			return $data->writeImage($filename);
		}
		return false;
	}

	public static function uploadFile($key,$filename,$type,$acl='private')
	{
		$config = Config::get('hdd');
		if (!file_exists($filename) || filesize($filename) == 0)
			return false;
		if (!file_exists(dirname($config['uploadpath'].$key)))
			mkdir(dirname($config['uploadpath'].$key), 0755, true);
		copy($filename, $config['uploadpath'].$key);
		return true;
	}

	public static function rename($srckey,$dstkey,$acl='private')
	{
		$config = Config::get('hdd');
		if (file_exists($config['uploadpath'].$srckey)) {
			if (file_exists($config['uploadpath'].$dstkey))
				unlink($config['uploadpath'].$dstkey);
			if (!file_exists(dirname($config['uploadpath'].$dstkey)))
				mkdir(dirname($config['uploadpath'].$dstkey), 0755, true);
			rename($config['uploadpath'].$srckey, $config['uploadpath'].$dstkey);
		}
	}

	public static function delete($key)
	{
		$config = Config::get('hdd');
		if (file_exists($config['uploadpath'].$key))
			unlink($config['uploadpath'].$key);
	}

	public static function url($key,$expires=null)
	{
		$config = Config::get('hdd');
		return $config['uploadurl'].$key;
	}

}

