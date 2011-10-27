<?php
/*
 * This is a simple MODEL representing a comment.
 */
class Comment
{
	/*
	 * Some vairables are encapsulated. Whilst others are not.
	 * Wicket, and therefore DangerFrame, supports this.
	 */
	public $name;
	protected $comment;
	protected $email;
	protected $datetime;
	protected $hereAboutUs;
	public $subscribe;
	
	public function __construct()
	{
		/*
		 * Default values
		 */
		$this->datetime = new DateTime("now");
	}
	public function getComment()
	{
		return $this->comment;
	}
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	public function getEmail()
	{
		return $this->email;
	}
	public function setEmail($email)
	{
		$this->email = $email;
	}
	public function getDateTime()
	{
		return $this->datetime;
	}
	public function getHereAboutUs()
	{
		return $this->hereAboutUs;
	}
	public function setHereAboutUs($value)
	{
		$this->hereAboutUs = $value;
	}
	/*
	 * Returns a list of all the visitor sources we may expect
	 */
	public static function hereAboutUsOptions()
	{
		return new DangerFrame_List
			(
				array(
					'Online advertising',
					'A search engine',
					'A friend',
					'A magazine',
					'Other',
				)
			);
	}
	public function insert()
	{
		self::getList()->append($this);
	}
	public static function getList()
	{		
		DangerFrame_Session::get();	//Ensure session is started.
		if(!isset($_SESSION['commentList'])) 
			$_SESSION['commentList'] = new DangerFrame_List();
		return $_SESSION['commentList'];
	}
}
?>