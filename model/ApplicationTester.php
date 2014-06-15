<?php

/**
 * Row object for 'application_tester' table.
 */
class ApplicationTester extends mfwObject {
	const DB_CLASS = 'ApplicationTesterDb';
	const SET_CLASS = 'ApplicationTesterSet';

	public function getTesterMail(){
		return $this->value('tester_mail');
	}
	public function getAppId(){
		return $this->value('app_id');
	}
}

/**
 * Set of ApplicationTester objects.
 */
class ApplicationTesterSet extends mfwObjectSet {
	public static function hypostatize(Array $row=array())
	{
		return new ApplicationTester($row);
	}
	protected function unsetCache($id)
	{
		parent::unsetCache($id);
	}

	public function getMailArray()
	{
		return $this->getColumnArray('tester_mail');
	}

	public function noticeNewComment(Comment $comment,Application $app)
	{
		$pkg = null;
		if($comment->getPackageId()){
			$pkg = PackageDb::retrieveByPK($comment->getPackageId());
		}
		$page_url = mfwRequest::makeURL("/app/comment?id={$app->getId()}#comment-{$comment->getNumber()}");
		ob_start();
		include APP_ROOT.'/data/notice_comment_mail_template.php';
		$body = ob_get_clean();

		$addresses = $this->getColumnArray('tester_mail');
		if(empty($addresses)){
			return;
		}

		$subject = "New Comment to {$app->getTitle()}";
		$sender = Config::get('mail_sender');
		$to = implode(', ',$addresses);
		$header = "From: $sender";

		mb_language('uni');
		mb_internal_encoding('UTF-8');
		return !mb_send_mail($to,$subject,$body,$header);
	}
}

/**
 * database accessor for 'application_tester' table.
 */
class ApplicationTesterDb extends mfwObjectDb {
	const TABLE_NAME = 'application_tester';
	const SET_CLASS = 'ApplicationTesterSet';

	public static function selectByAppId($app_id)
	{
		$query = "WHERE app_id = ?";
		return static::selectSet($query,array($app_id));
	}

	public static function selectByTesterMail($mail)
	{
		$query = "WHERE tester_mail = ?";
		return static::selectSet($query,array($mail));
	}

	public static function deleteTester($app_id,array $testers,$con=null)
	{
		$bind = array(':app_id' => $app_id);
		$ph = self::makeInPlaceHolder($testers,$bind,'tester_mail');

		$sql = "DELETE FROM application_tester WHERE app_id = :app_id AND tester_mail IN ($ph)";
		return mfwDBIBase::query($sql,$bind,$con);
	}

	public static function addTester($app_id,$testers,$con=null)
	{
		$bind = array(':app_id' => $app_id);
		$values = array();
		foreach($testers as $k=>$v){
			$key = ":mail_$k";
			$values[] = "(:app_id,$key)";
			$bind[$key] = $v;
		}
		$sql = 'INSERT INTO application_tester (app_id,tester_mail) VALUES '
			. implode(',',$values);
		return mfwDBIBase::query($sql,$bind,$con);
	}

}

