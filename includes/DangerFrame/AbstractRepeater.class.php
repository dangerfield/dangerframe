<?php
abstract class DangerFrame_AbstractRepeater extends DangerFrame_WebMarkupContainer 
{
	public function initDOM(DomNode $DOMNode)
	{
		parent::initDOM($DOMNode);

		$this->onPopulate();
		
		$loopstart = true;
		foreach($this->renderIterator() AS $i => $object)
		{
			if($loopstart)
				$object->initDOM($DOMNode);
			else
				$object->initDOM($this->getInsertClone());
			$loopstart = false;
		}
	}
	protected abstract function onPopulate();
	
	public function render()
	{	
		if(!$this->renderIterator()->valid())
			$this->remove();
		
		foreach($this->renderIterator() AS $object)
		{
			$this->renderChild($object);
		}
	}
	protected function renderChild(DangerFrame_Component $child)
	{
		$child->render();
	}
	abstract protected function renderIterator();
}