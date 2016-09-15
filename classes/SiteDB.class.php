<?php

class SiteDB{

	private $db;

	public function __construct(){
		try{
			$this->db = new PDO('sqlite:' . dirname(__FILE__) . '/../db/taberna.s3db');
		}catch(PDOException $e){
			$this->db = false;
		}
	}

	public static function getInstance(){
		static $instance;

		if($instance == null)
			$instance = new SiteDB();

		return $instance;
	}

	public function exec($sql){
		$this->db->exec($sql);
	}

	public function query($sql){
		if($this->db === false)
			return array();

		return $this->db->query($sql);
	}

	static public function queryResult($sql){
		$db = SiteDB::getInstance();

		$data = $db->query($sql);

		$result = array();
		foreach($data as $row)
			$result[] = $row;

		return $result;
	}

	static public function getTexts(){
		return SiteDB::queryResult("SELECT * FROM texto ORDER BY date_published DESC");
	}


	static public function getTextByGuid($id){
		$result = SiteDB::queryResult("SELECT * FROM texto WHERE guid = '$id' LIMIT 1");
		if(count($result) > 0)
			return $result[0];

		header("404 Not Found");
		exit;
	}

	static public function getPodcasts(){
		return SiteDB::queryResult("SELECT * FROM podcast ORDER BY date_published DESC");
	}


	static public function getPodcastByGuid($id){
		$result = SiteDB::queryResult("SELECT * FROM podcast WHERE guid = '$id' LIMIT 1");
		if(count($result) > 0)
			return $result[0];

		header("404 Not Found");
		exit;
	}

	static public function getHomeItems(){
		$result = SiteDB::queryResult("
		SELECT * FROM (
			SELECT 
					title           AS title,
					guid            AS guid,
					date_published  AS date_published,
					'Taberneiro'    AS user,
					'podcast'		AS type
				FROM
				 	podcast
			UNION
				SELECT
					title          AS title,
					guid           AS guid,
					date_published AS date_published,
					user           AS user,
					'textos'       AS type
				FROM 
					texto
		) ORDER BY date_published DESC LIMIT 10");

		return $result;
	}

	static private function getClienteIP(){
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		if(isset($_SERVER['HTTP_X_FORWARDED']))
			return $_SERVER['HTTP_X_FORWARDED'];
		if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			return $_SERVER['HTTP_FORWARDED_FOR'];
		if(isset($_SERVER['HTTP_FORWARDED']))
			return $_SERVER['HTTP_FORWARDED'];
		if(isset($_SERVER['REMOTE_ADDR']))
			return $_SERVER['REMOTE_ADDR'];
		
		return 'UNKNOWN';
	}

	static public function saveMessageInbox($name, $email, $message){
		$result = SiteDB::queryResult("SELECT COUNT(id) as numMessages FROM inbox");
		
		if($result[0]["numMessages"] > 99)
			return;

		try {
			$db = SiteDB::getInstance();
			$db->query("INSERT INTO inbox (name, email, message, ip) VALUES ('".addslashes($name)."', '".addslashes($email)."', '".addslashes($message)."', '".addslashes(SiteDB::getClienteIP())."')");
		} catch(Exception $e) {
			return false;
		}

		return true;
	}

}

?>