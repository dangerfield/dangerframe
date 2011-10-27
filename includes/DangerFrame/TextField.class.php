<?php
class DangerFrame_TextField extends DangerFrame_AbstractTextComponent
{
	public function onDOMNode()
	{
		parent::onDOMNode();
		$this->checkComponentTag('input');
		$this->checkComponentTagAttribute('type', $this->getInputType());
	}
	protected function getInputType()
	{
		return 'text';
	}
	public function render()
	{
		parent::render();
		$this->getDOMNode()->setAttribute('value', $this->getValue());
	}
}