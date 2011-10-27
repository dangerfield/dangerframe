<?php
class DangerFrame_PropertyModel extends DangerFrame_AbstractPropertyModel implements DangerFrame_iModel
{
	protected $propertyExpression;
	public function __construct($modelObject, $propertyExpression = null)
	{
		parent::__construct($modelObject);
		$this->propertyExpression = $propertyExpression;
	}
	protected function propertyExpression()
	{
		return $this->propertyExpression;
	}
}
?>