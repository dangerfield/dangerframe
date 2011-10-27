<?php
/*
 * The inclusion of DangerFrame! This initiates the framework.
 */
require_once('includes/inc.php');

/*
 * A very basic Model.
 * This would normally be stored elsewhere.
 * It is here for simplifying the example.
 */
class Item
{
	protected $limes;
	protected $grapefruits;
	protected $oranges;
	public function __construct($limes, $grapefruits, $oranges)
	{
		$this->limes		= $limes;
		$this->grapefruits	= $grapefruits;
		$this->oranges		= $oranges;
	}
	public function limes()
	{
		return $this->limes;
	}
	public function grapefruits()
	{
		return $this->grapefruits;
	}
	public function oranges()
	{
		return $this->oranges;
	}
}

/*
 * An extension of DangerFrame_Loop to populate items
 */
class extendedLoop extends DangerFrame_Loop
{
	protected $i = 0;
	public function populateItem(DangerFrame_LoopItem $item)
	{
		$item->add(
			new DangerFrame_Label('sub','Grapes '.($this->i++).' successful'));
	}
}

/*
 * An extension of DangerFrame_ListView to populate items
 */
class extendedListView extends DangerFrame_ListView
{
	public function populateItem(DangerFrame_ListItem $item)
	{
		$item->add(
			new DangerFrame_Label('limes',			$item->getModelObject()->limes()),
			new DangerFrame_Label('grapefruits',	$item->getModelObject()->grapefruits()),
			new DangerFrame_Label('oranges',		$item->getModelObject()->oranges()));
	}
}

/*
 * The page controller. Notice the class name matches the filename
 */
class basic extends DangerFrame_Page
{

	public function __construct()
	{
		parent::__construct();
		
		/*
		 * All the page components are added to the page.
		 * Admire the beauty...
		 */
		$this->add(
			new DangerFrame_Label('apples','Apple test successful'));
		$this->add(
			new DangerFrame_MultiLineLabel('oranges','Orange test successful!
				
		Orange test successful (line 2)'));
		
		$pears = $this->add(
			new DangerFrame_WebMarkupContainer('pears'));
		$pears->add(
			new DangerFrame_Label('pineapples','Pears & Pinapples test successful'));
		$bananas = $this->add(
			new DangerFrame_RepeatingView('bananas'));
		$bananas->add(
			new DangerFrame_Label('1','Bananas 1 successful'));
		$bananas->add(
			new DangerFrame_Label('2','Bananas 2 successful'),
			new DangerFrame_Label('3','Bananas 3 successful'));
		$grapes = $this->add(
			new extendedLoop('grapes', 5));
		$lemons = $this->add(
			new extendedListView('lemons',
				new DangerFrame_List(
					array(
						new Item('A','B','C'),
						new Item('D','E','F'),
						new Item('G','H','I'),
					)
				)
			)
		);
	}
}

?>