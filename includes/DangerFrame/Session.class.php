<?php
class DangerFrame_Session
{
	protected static $instance;
	public static function get()
	{
		if(!is_null(self::$instance))
			return self::$instance;
		else
			return self::findOrCreate();
	}
	public static function findOrCreate()
	{
		session_start();
		if(isset($_SESSION['_-dangerframe-']))
			return self::find();
		else
			return self::create();
	}
	public static function create()
	{
		self::$instance = new self();
		return self::$instance;
	}
	public static function find()
	{
		self::$instance = unserialize($_SESSION['_-dangerframe-']);
		return self::$instance;
	}
	
	public function __destruct()
	{
		$_SESSION['_-dangerframe-'] = serialize(self::$instance);
	}
	
	
	private $feedbackMessages;
	public function __construct()
	{
		$this->feedbackMessages = new DangerFrame_FeedbackMessages();
	}
	
	
	public function getFeedBackMessages()
	{
		return $this->feedbackMessages;
	}
}