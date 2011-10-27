<?php
/*
 * This is the CONTROLLER file for the comment file.
 * 
 * The first class controls the form.
 * 
 * The second the list of existing comments
 * 
 * The last one controls the page, adding the form and the list to it.
 */

/*
 * Include and initialize DANGERFRAME
 */
require_once('includes/inc.php');

/*
 * Include the comment MODEL. Seperated for true MVC.
 */
require_once('includes/models/Comment.class.php');

/*
 * The form object. Extended from DangerFrame_Form for modifications
 */
class commentForm extends DangerFrame_Form
{
	protected $comment;
	
	public function __construct($DFId)
	{
		parent::__construct($DFId);
		$this->comment = new Comment();
		$this->add(
			new DangerFrame_TextField		('name',			new DangerFrame_PropertyModel($this->comment,'name'			)),
			new DangerFrame_TextArea		('comment',			new DangerFrame_PropertyModel($this->comment,'comment'		)),
			new DangerFrame_Checkbox		('subscribeOption',	new DangerFrame_PropertyModel($this->comment,'subscribe'	)),
			new DangerFrame_DropDownChoice	('source',			new DangerFrame_PropertyModel($this->comment,'hereAboutUs'), Comment::hereAboutUsOptions())
		);
		$email = $this->add(
			new DangerFrame_TextField		('email',			new DangerFrame_PropertyModel($this->comment,'email'		)));
		$email->add(new DangerFrame_EmailAddressValidator);
	}
	public function onSubmit()
	{
		$this->comment->insert();
	}
}

/*
 * The list of comments. Extended from DangerFrame_ListView
 */
class commentList extends DangerFrame_ListView
{
	public function populateItem(DangerFrame_ListItem $item)
	{
		$item->add(
			new DangerFrame_Label(			'date',		$item->getModelObject()->getDateTime()->format('F j, Y, g:i a')),
			new DangerFrame_Label(			'name',		$item->getModelObject()->name),
			new DangerFrame_MultiLineLabel(	'comment',	$item->getModelObject()->getComment())
			);
	}
}
/*
 * The page controller. Notice the class name matches the filename again
 */
class comments extends DangerFrame_Page
{
	public function __construct()
	{
		parent::__construct();
		
		$comments = Comment::getList();
		
		$form = $this->add(new commentForm('commentForm'));
		$list = $this->add(new commentList('commentList', $comments));
	}
}
?>