<?php
class DangerFrame_ChoiceRender implements DangerFrame_IChoiceRenderer
{
	private $displayExpression = null;
	private $idExpression = null;
	
	public function __construct($displayExpression = null, $idExpression = null)
	{
		$this->displayExpression	= $displayExpression;
		$this->idExpression			= $idExpression;
	}
	
	public function getDisplayValue($object)
	{
		$returnValue = $object;
		if(!is_null($this->displayExpression) && !is_null($object))
			$returnValue = DangerFrame_ProperyResolver::getValue($this->displayExpression, $object);
		if(is_null($returnValue))
			return '';
		return $returnValue;
	}
	
	public function getIdValue($object, $index)
	{
		if(is_null($this->idExpression))
			return $index;
		if(is_null($object))
			return '';
		$returnValue = DangerFrame_PropertyResolver::getValue($this->idExpression, $object);
		if(is_null($returnValue))
			return '';
			
		return $returnValue;
	}
}