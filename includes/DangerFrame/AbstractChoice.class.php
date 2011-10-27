<?php
abstract class DangerFrame_AbstractChoice extends DangerFrame_FormComponent
{
	private $choices;
	private $renderer;
	public function __construct($DFId, DangerFrame_iModel $iModel, $choices, DangerFrame_IChoiceRenderer $renderer = null)
	{
		parent::__construct($DFId, $iModel);
		$this->setChoices($choices);
		$this->setChoiceRenderer($renderer);
	}
	public function getChoiceRenderer()
	{
		return $this->renderer;
	}
	public function getChoices()
	{
		return $this->choices->getObject();
	}
	public function setChoices($choices)
	{
		if($choices instanceof DangerFrame_iModel)
		{
			$this->choices = $choices;
		}
		else if($choices instanceof DangerFrame_List)
		{
			$this->choices = new DangerFrame_WildcardListModel($choices);
		}
		else
			throw new RunTimeException();
	}
	public function setChoiceRenderer(DangerFrame_IChoiceRenderer $renderer = null)
	{
		if(is_null($renderer))
			$this->renderer = new DangerFrame_ChoiceRender();
		else
			$this->renderer = $renderer;
	}
	protected function getDefaultChoice($selected)
	{
		return null;
	}
	protected abstract function isSelected($object, $index, $selected);
	protected function isDisabled($object, $index, $selected)
	{
		return false;
	}
	protected function appendOption($choice, $index, $selected)
	{
		$objectValue = $this->renderer->getDisplayValue($choice);
		$displayValue = '';
		if(is_object($objectValue)){
			$objectClass = get_class($objectValue);
			$converter = $this->getConverter($objectClass);
			$displayValue = $converter->convertToString($objectValue);
		}else
			$displayValue = $objectValue;
			
		$domChoice = DangerFrame_Builder::getDOMDocument()->createElement('option');
		
		if($this->isSelected($choice, $index, $selected))
			$domChoice->setAttribute('selected','selected');
		if($this->isDisabled($choice, $index, $selected))
			$domChoice->setAttribute('disabled','disabled');
		$domChoice->setAttribute('value',$this->renderer->getIdValue($choice, $index));
		$domChoice->appendChild(new DomText($displayValue));
		
		return $domChoice;
	}
	public function render()
	{
		parent::render();
		$selected = $this->getValue();
		$default = $this->getDefaultChoice($selected);
		if($default instanceof DomElement)
			$this->getDOMNode()->appendChild($default);
		foreach ($this->getChoices() AS $index => $choice)
		{
			$this->getDOMNode()->appendChild($this->appendOption($choice, $index, $selected));
		}
	}
}