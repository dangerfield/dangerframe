<?php
class DangerFrame_GenericBaseModel implements DangerFrame_iModel
{
	protected $object;
	public function getObject()
	{
		return $this->object;
	}
	public function setObject($object)
	{
		$this->object = $object;
	}
}