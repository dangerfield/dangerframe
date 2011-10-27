<?php
class DangerFrame_TextArea extends  DangerFrame_AbstractTextComponent
{
	public function onDOMNode()
	{
		parent::onDOMNode();
		$this->checkComponentTag('textarea');
	}
	public function render()
	{
		parent::render();
		$this->removeChildren();
		
		$this->getDOMNode()->appendChild(new DomText($this->getValue()));
	}
}