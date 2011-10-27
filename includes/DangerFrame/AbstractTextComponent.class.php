<?php
abstract class DangerFrame_AbstractTextComponent extends DangerFrame_FormComponent
{
	protected $convertEmptyStringToNull = true;
	protected function convertInput()
	{
		$this->resolveType();
		parent::convertInput();
	}
	protected function convertValue(array $value)
	{
		$tmp = !is_null($value) && count($value) > 0 ? $value[0] : null;
		if ($this->getConvertEmptyInputStringToNull() && strlen($tmp) == 0)
		{
			return null;
		}
		return parent::convertValue($value);
	}
	public function getConvertEmptyInputStringToNull()
	{
		return $this->convertEmptyStringToNull;
	}
	private function getModelType(DangerFrame_IModel $model)
	{
		if ($model instanceof DangerFrame_IObjectClassAwareModel)
			return $model->getObjectClass();
		else
			return null;
	}
	public function isInputNullable()
	{
		return false;
	}
	//protected function onBeforeRender(){}
	private function resolveType()
	{
		if(is_null($this->getType()))
		{
			$type = $this->getModelType($this->getDefaultModel());
			$this->setType($type);
		}			
	}
	public function setConvertEmptyInputStringToNull($flag)
	{
		$this->convertEmptyStringToNull = (bool) $flag;
	}
	//protected function supportsPersistence(){}
}