<?php
abstract class DangerFrame_AbstractSingleSelectChoice extends DangerFrame_AbstractChoice
{
	const CHOOSE_ONE = 'Choose One';
	const NO_SELECTION_VALUE = "-1";
	
	private $nullValid = false;
	
	public function getModelValue()
	{
		$object = $this->getModelObject();
		if(!is_null($object))
		{
			$index = $this->getChoices()->indexOf($object);
			return $this->getChoiceRenderer()->getIdValue($object, $index);
		}
		return $this->getNoSelectionValue();
	}
	public function isNullValid()
	{
		return $this->nullValid;
	}
	public function setNullValid($nullValid)
	{
		$this->nullValid = (bool) $nullValid;
	}
	protected function convertValue(array $value)
	{
		$tmp = (($value != null) && (count($value)>0)) ? $value[0] : null;
		return $this->convertChoiceIdToChoice($tmp);
	}
	protected function convertChoiceIdToChoice($id)
	{
		$choices = $this->getChoices();
		$renderer = $this->getChoiceRenderer();
		foreach($choices AS $index => $choice)
			if($renderer->getIdValue($choice, $index) == $id)
				return $choice;
		return null;
	}
	protected function getDefaultChoice($selected)
	{
		$domDefault = DangerFrame_Builder::getDOMDocument()->createElement('option');
		
		if($this->isNullValid())
		{			
			if(is_null($selected))
				$domDefault->setAttribute('selected','selected');
			$domDefault->setAttribute('value','');
			$domDefault->appendChild(new DomText(self::CHOOSE_ONE));
			return $domDefault;
		}
		else
		{
			if(is_null($selected) || $this->getNoSelectionValue() == $selected || "" === $selected)
			{
				$domDefault->setAttribute('selected','selected');
				$domDefault->setAttribute('value','');
				$domDefault->appendChild(new DomText(self::CHOOSE_ONE));
				return $domDefault;
			}	
		}
		return null;
	}
	private function getNullValidKey()
	{
		return $this->getId() . '.nullValid';
	}
	private function getNullKey()
	{
		return $this->getId() . '.null';
	}
	protected function isSelected($object, $index, $selected)
	{
		return (!is_null($selected) && $selected == $this->getChoiceRenderer()->getIdValue($object, $index));
	}
	protected function getNoSelectionValue()
	{
		return self::NO_SELECTION_VALUE;
	}
}