<?php
class DangerFrame_Checkbox extends DangerFrame_FormComponent
{
	public function onDOMNode()
	{
		parent::onDOMNode();
		$this->checkComponentTag('input');
		$this->checkComponentTagAttribute('type', 'checkbox');
	}
	public function render()
	{
		parent::render();
		if($this->getValue())
			$this->getDOMNode()->setAttribute('checked', 'checked');
		else
			$this->getDOMNode()->removeAttribute('checked');
	}
} 