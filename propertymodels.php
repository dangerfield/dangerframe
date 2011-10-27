<?php
require_once('includes/inc.php');

/*
 * Slightly more advanced Comment object.
 * Stored here for simplicity.
 */
class Comment
{
	protected	$text;
	protected	$name;
	protected	$datetime;
	protected	$subscribe = true;	//Note the default value
	public		$user;				//Note this is not encapsulated.
	public $type = 'test';			//Not ecapsulated, default value.
	
	public function __construct()
	{
		/*
		 * Default values can be set here too...
		 */
		$this->text		= 'A Default Comment In A New Comment';
		$this->name		= 'name';
		$this->datetime = new DateTime();
		$this->user		= new User;
	}
	public function getText()
	{
		return $this->text;
	}
	public function setText($text)
	{
		$this->text = $text;
	}
	public function getName()
	{
		return $this->name;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
	}
	public function getDatetime()
	{
		return $this->datetime;
	}
	public function getSubscribe()
	{
		return $this->subscribe;
	}
	public function setSubscribe($subscribe)
	{
		$this->subscribe = $subscribe;
	}
}

/*
 * User object. Used for storing User details within the Comment class.
 */
class User
{
	public $username	= 'Anonymous';
	public $email		= 'email@example.com';
}

class extendedForm extends DangerFrame_Form
{
	protected $comment;
	
	public function __construct($DFId)
	{
		parent::__construct($DFId);
		$comment = new Comment();
		$this->add(	new DangerFrame_TextField(		'text1',	new DangerFrame_PropertyModel($comment,'user->username')));	//Accesses 'username' property within the User object, stored under 'user' in the Comment object
		$this->add(	new DangerFrame_TextField(		'text2',	new DangerFrame_PropertyModel($comment,'user->email')));
		$this->add(	new DangerFrame_TextArea(		'textarea',	new DangerFrame_PropertyModel($comment,'text')));			//Simple expression accessing 'text' property of the Comment object
		$this->add(	new DangerFrame_Checkbox(		'checkbox',	new DangerFrame_PropertyModel($comment,'subscribe')));
		$this->add(	new DangerFrame_DropDownChoice(	'select',	new DangerFrame_PropertyModel($comment,'type'), new DangerFrame_List(array('test', 'test2', 'test3'))));
	}
	/*
	 * Nothing is done with returned comment, for example simplification.
	 */
}

class propertymodels extends DangerFrame_Page
{

	public function __construct()
	{
		parent::__construct();
		$form = $this->add(new extendedForm('formA'));
	}
}

/*
 * And here is the View.
 * A demonstration and test of combining Models & Views
 */
?>
<html>
<head>
<title>Property Models Example</title>
</head>
<body xmlns:df="dangerframe">
<h1>Property Models Example</h1>
<form df:id="formA" method="post">
<h3>Text field</h3>
<input df:id="text1" type="text" name="text" value=""/>
<input df:id="text2" type="text" name="text2" value=""/>
<textarea name="textarea" rows="5" cols="5" df:id="textarea"></textarea>
<input type="checkbox" name="checkbox" df:id="checkbox"/>
<select name="select" df:id="select">
</select>
<input df:id="submit" type="submit" name="submit" value="Submit!"/>
</form>
</body></html>