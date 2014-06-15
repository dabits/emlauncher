<?php
require_once APP_ROOT.'/model/Config.php';

class HDD {
	
	const URL_TOKEN_PREFIX = 'hdd_file_token_';
	const URL_FILE_PREFIX = 't_';
	

	protected function __construct()
	{
		$config = Config::get('hdd');
	}
	
	protected static function generate_file_path($key)
	{
		$config = Config::get('hdd');
		$dir_name = $config['uploadpath'] . dirname($key);
		
		if(!is_dir($dir_name)){
			mkdir($dir_name, 0755, true);
		}
		return $config['uploadpath'] . $key;
	}
	
	protected static function get_file_path($key)
	{
		$config = Config::get('hdd');
		return $config['uploadpath'] . $key;
	}

	public static function uploadData($key,$data,$type,$acl='private')
	{
		$file_path = self::generate_file_path($key);

		switch(get_class($data))
		{
		case 'Imagick':
			return $data->writeImage($file_path);
		}
		return false;
	}

	public static function uploadFile($key,$filename,$type,$acl='private')
	{
		if (!file_exists($filename) || filesize($filename) == 0) return false;
		return copy($filename, self::generate_file_path($key));
	}

	public static function rename($srckey,$dstkey,$acl='private')
	{
		$src_file_path = self::get_file_path($srckey);
		$dst_file_path = self::generate_file_path($dstkey);
		return rename($src_file_path, $dst_file_path);
	}

	public static function delete($key)
	{
		$file_path = self::get_file_path($key);
		return unlink($file_path);
	}

	public static function url($key,$expires=null)
	{
		// create dummy url.
		if($expires){
			$token = Random::string(16);
			$file_info = pathinfo($key);
			$new_key = $file_info['dirname'] . '/' . self::URL_FILE_PREFIX . $token . '.' . $file_info['extension'];
			$expire = strtotime($expires, 0);
			mfwMemcache::set(self::URL_TOKEN_PREFIX.$token,$key,$expire);
			$key = $new_key;
		}
		$config = Config::get('hdd');
		return $config['uploadurl'].$key;
	}

}

