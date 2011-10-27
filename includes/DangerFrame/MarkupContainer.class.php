<?php
abstract class DangerFrame_MarkupContainer extends DangerFrame_Component implements IteratorAggregate
{
	protected $subComponents;
	
	public function __construct($DFId, DangerFrame_iModel $iModel = null)
	{
		parent::__construct($DFId, $iModel);
		$this->subComponents = new ArrayObject;
	}
	public function add($object)
	{
		if(!$object instanceof DangerFrame_Component)
			throw new RuntimeException();
		if(func_num_args() > 1)
			foreach(func_get_args() AS $arg)
				$this->add($arg);
		else
		{	
			$this->subComponents->offsetSet($object->getId(), $object);
			$this->addedComponent($object);
			return $object;
		}
	}
	private function addedComponent(DangerFrame_Component $component)
	{
		if($component == $this)
			new RuntimeException();
		
		$parent = $component->getParent();
		
		if($parent != null)
			$parent->remove($component);
		
		$component->setParent($this);
		/*
		$page = $this->findPage();
		
		if($page != null)
			$page->componentAdded($component);
		*/
	}
	public function contains($string)
	{
		$xpath = new DOMXPath(DangerFrame_Builder::getDOMDocument());
		$xpath->registerNamespace('df', 'dangerframe');
		$nodeList = $xpath->query('.//*[@df:id=\'' . $string . '\']',$this->getDOMNode());

		return ($nodeList->length > 0);
	}
	protected function get($string)
	{
		$xpath = new DOMXPath(DangerFrame_Builder::getDOMDocument());
		$xpath->registerNamespace('df', 'dangerframe');
		$nodeList = $xpath->query('.//*[@df:id=\'' . $string . '\']',$this->getDOMNode());

		if($nodeList->length == 0)
			throw new RuntimeException('DFId "'.$this->getId().'" not found.');
		else
			return $nodeList->item(0);
	}
	public function getIterator()
	{
		return $this->subComponents->getIterator();
	}
	public function initDOM(DomNode $DOMNode)
	{
		parent::initDOM($DOMNode);
		foreach($this->subComponents AS $subComponent)
			$this->initDOMSubComponent($subComponent);
	}
	public function initDOMSubComponent(DangerFrame_Component $subComponent)
	{
		if($this->contains($subComponent->getId()))
			$subComponent->initDOM($this->get($subComponent->getId()));
	}
	
	public function render()
	{
		if(!is_object($this->subComponents)) throw new Exception('er?');
		foreach($this->subComponents AS $subComponent)
		{
			$subComponent->render();
		}
	}
	public function remove($something = null)
	{
		//$this->subComponents->offsetUnset($DFId);
		$this->getDOMNode()->parentNode->removeChild($this->getDOMNode());
		//$this->getDOMNode()->removeChild($this->get($DFId));
	}
	public function size()
	{
		return $this->subComponents->count();
	}
	public function visitChildren(DangerFrame_ComponentIVisitor $visitor, $class = null)
	{
		foreach($this->subComponents AS $child)
		{
			$value = null;
			if(is_null($class) || $child instanceof $class)
			{
				$value = $visitor->component($child);
				if (($value != DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL) &&
					($value != DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER))
				{
					return $value;
				}
			}
			if(($child instanceof DangerFrame_MarkupContainer) &&
				($value != DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER))
			{
				$value = $child->visitChildren($visitor, $class);
				if (($value != DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL) &&
					($value != DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER))
				{
					return $value;
				}
			}
		}
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
}