<?php

class uploadedActions extends MainActions
{

	protected $mime_types = array(
		'ipa' => 'application/octet-stream',
		'apk' => 'application/vnd.android.package-archive',

		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
	);

	public function executeDefaultAction()
	{
		$config = Config::get('hdd');
		$file_name = str_replace('/uploaded/', '', $_SERVER['PATH_INFO']);
		$file_path = $config['uploadpath'] . $file_name;
		$file_info = pathinfo($file_path);
		if(strpos($file_info['filename'], HDD::URL_FILE_PREFIX) !== FALSE){
			$key = mfwMemcache::get(HDD::URL_TOKEN_PREFIX.str_replace(HDD::URL_FILE_PREFIX, '', $file_info['filename']));
			if(!$key) return array(array(self::HTTP_404_NOTFOUND),'404 Not Found');
			$file_path = $config['uploadpath'] . $key;
		}
		$mime_type = $this->mime_types[$file_info['extension']];
		if(file_exists($file_path)){
			header('content-type:'.$mime_type);
			readfile($file_path);
		}else{
			array(array(self::HTTP_404_NOTFOUND),'404 Not Found');
		}
	}

}
