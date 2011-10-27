<?php
abstract class DangerFrame_AbstractPropertyModel implements DangerFrame_iModel, DangerFrame_IObjectClassAwareModel
{
	protected $modelObject;
	protected $target;
	public function __construct($modelObject)
	{
		$this->modelObject	= $modelObject;
		$this->target		= $modelObject;
	}
	public function getObject()
	{
		if(is_null($this->propertyExpression()))
			return $this->getTarget();
		
		return DangerFrame_PropertyResolver::getValue($this->propertyExpression, $this->modelObject);
	}
	public function getObjectClass()
	{
		return DangerFrame_PropertyResolver::getPropertyClass($this->propertyExpression, $this->modelObject);
	}
	public function setObject($modelObject)
	{
		if(is_null($modelObject)) return null;
		$this->getPropertySetter()->invoke($modelObject);
	}
	public function getPropertyExpression()
	{
		return $this->propertyExpression();
	}
	/*
	public function &getPropertyField()
	{
		$expr = $this->propertyExpression();
		$field = $this->modelObject->$expr;
		if(isset($field))
			return $field;
		else
			return null;
	}
	*/
	public function getPropertyGetter()
	{
		return DangerFrame_PropertyResolver::getPropertyGetter($this->propertyExpression, $this->modelObject);
	}
	public function getPropertySetter()
	{
		return DangerFrame_PropertyResolver::getPropertySetter($this->propertyExpression, $this->modelObject);
	}
	
	abstract protected function propertyExpression();
	public function getTarget()
	{
		$object = $this->target;
		return $object;
		while($object instanceof DangerFrame_iModel)
		{
			$temp = $object->getObject();
			if($temp == $object)	break;
			$object = $temp;
		}
		return $object;
	}
	
}