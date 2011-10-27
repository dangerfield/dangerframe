<?php
class DangerFrame_ListItem extends DangerFrame_MarkupContainer
{
	protected $index;
	public function __construct($index, $object)
	{
		parent::__construct($index);
		$this->index = $index;
		$this->setModelObject($object);
	}
	public function getIndex()
	{
		return $this->index;
	}
	protected $modelObject;
	protected function setModelObject($object)
	{
		$this->modelObject = $object;
	}
	public function getModelObject()
	{
		return $this->modelObject;
	}
}