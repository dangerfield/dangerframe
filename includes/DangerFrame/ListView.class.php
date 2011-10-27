<?php
abstract class DangerFrame_ListView extends DangerFrame_AbstractRepeater
{
	protected $list;
	protected $startIndex = 0;
	protected $maxViewSize;
	public function __construct($DFId, DangerFrame_iList $list = null)
	{
		parent::__construct($DFId);
		if($list instanceof DangerFrame_iList)
			$this->setList($list);
	}
	public function getList()
	{
		return $this->list;
	}
	public function setList(DangerFrame_List $list)
	{
		$this->list = $list;
	}
	public function getStartIndex()
	{
		return $this->startIndex;
	}
	public function setStartIndex($index)
	{
		$this->startIndex = $index;
	}
	public function getViewSize()
	{
		if(is_null($this->maxViewSize))
			return $this->getList()->count() - $this->getStartIndex();
		else
			return min($this->maxViewSize, $this->getList()->count()-$this->getStartIndex());
	}
	public function setViewSize($size)
	{
		$this->maxViewSize = $size;
	}
	protected function newItem($index)
	{
		return new DangerFrame_ListItem($index, $this->list->offsetGet($index));
	}
	protected function onPopulate()
	{
		for($i=$this->getStartIndex(); $i< $this->getViewSize(); $i++)
		{
			$item = $this->newItem($i);
			$this->add($item);
			$this->populateItem($item);
		}	
	}
	abstract protected function populateItem(DangerFrame_ListItem $item);
	protected function renderItem(DangerFrame_ListItem $item){}
	protected function renderChild(DangerFrame_Component $child)
	{
		$this->renderItem($child);
		$child->render();
	}
	protected function renderIterator()
	{
		return $this->subComponents->getIterator();
	}
}