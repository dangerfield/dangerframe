<?php
class DangerFrame_Model implements DangerFrame_iModel
{
	public function __construct(Object $object = null)
	{
		if(!is_null($object))
			$this->setObject($object);
	}
	protected $object;
	public function getObject()
	{
		return $this->object;
	} 
	public function setObject(Object $object)
	{
		$this->object = $object;
	}
}