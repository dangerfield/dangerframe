<?php
class DangerFrame_DropDownChoice extends DangerFrame_AbstractSingleSelectChoice
{
	public function onSelectionChanged()
	{
		$this->convertInput();
		$this->updateModel();
		$this->onSelectionChanged($this->getModelObject());
	}
}