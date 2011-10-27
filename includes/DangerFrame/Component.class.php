<?php
class DangerFrame_Component
{
	protected $id;
	protected $defaultModel;
	protected $DOMDocumentFragment;
	protected $DOMParentNode;
	protected $DOMNextSibling;
	protected $DOMNode;
	protected $parent;
	
	public function __construct($id, DangerFrame_iModel $iModel = null)
	{
		$this->id		= $id;
		if($iModel instanceof DangerFrame_iModel)
			$this->setDefaultModel($iModel);
	}
	public function error($message)
	{
		DangerFrame_Session::get()->getFeedbackMessages()->error($this, $message->__toString());
	}
	public function hasErrorMessage()
	{
		return DangerFrame_Session::get()->getFeedbackMessages()->hasErrorMessageFor($this);
	}
	public function hasFeedbackMessage()
	{
		return DangerFrame_Session::get()->getFeedbackMessages()->hasMessageFor($this);
	}
	public function getId()
	{
		return $this->id;
	}
	public function setId($id)
	{
		if($this->DOMNode)
			$this->DOMNode->setAttributeNS('dangerframe', 'id', $id);
		$this->id	= $id;
	}
	public function getConverter($class_name)
	{
		if($class_name == 'DateTime')
			return new DangerFrame_DateTimeConverter;
	}
	public function getDefaultModel()
	{
		return $this->defaultModel;
	}
	public function getDefaultModelObject()
	{
		return $this->getDefaultModel()->getObject();
	}
	public function getDefaultModelObjectAsString($object = null)
	{
		if(is_null($object))
		{
			if(is_null($this->getDefaultModelObject()))
				return null;
			return $this->getDefaultModelObjectAsString($this->getDefaultModelObject());
		}
		if(!is_object($object))
			return $object;

		$class = get_class($object);
		$converter = $this->getConverter($class);
		
		$modelString = $converter->convertToString($object);
		
		if(!is_null($modelString))
			return $modelString;
		else
			return '';
		
	}
	public function setDefaultModel(DangerFrame_iModel $iModel)
	{
		$this->defaultModel = $iModel;
	}
	public function setDefaultModelObject($object)
	{
		$this->getDefaultModel()->setObject($object);
	}
	public function getDOMNode()
	{
		return $this->DOMNode;
	}
	protected function setDOMNode(DomNode $DOMNode)
	{
		$this->DOMNode			= $DOMNode;	
		
		if(! $this->DOMNode instanceof DomDocument )
		{
			$this->DOMParentNode	= $DOMNode->parentNode;
			$this->DOMNextSibling	= $DOMNode->nextSibling;
		}
		$this->onDOMNode();
	}
	protected function onDOMNode(){}

	public function initDOM(DomNode $DOMNode)
	{
		$this->setDOMNode($DOMNode);
	}

	public function render(){}
	
	public function appendChild(DomNode $DOMNode)
	{
		$this->DOMNode->appendChild($DOMNode);
	}
	public function appendSibling(DomNode $DOMNode)
	{
		return $this->DOMParentNode->insertBefore($DOMNode, $this->DOMNextSibling);
	}
	public function removeChildren()
	{
		if( $this->getDOMNode()->hasChildNodes() )
		{
			foreach( $this->getDOMNode()->childNodes AS $node )
			{
				$this->getDOMNode()->removeChild($node);
			}
		}
	}
	public function removeFromDOM()
	{
		$this->getDOMNode()->parentNode->removeChild($this->getDOMNode());
	}
	public function prepareClone()
	{
		return $this->getDOMNode()->cloneNode(true);
	}
	public function getInsertClone()
	{
		$clone = $this->prepareClone();
		return $this->appendSibling($clone);
	}
	public function getPage()
	{
		$page = $this->findPage();
		if(is_null($page))
			throw new RuntimeException('No page found for component');
		return $page;
	}
	public function findPage()
	{
		return ($this instanceof DangerFrame_Page ? $this : $this->findParent('DangerFrame_Page'));
	}
	public function getParent()
	{
		return $this->parent;
	}
	public function setParent(DangerFrame_MarkupContainer $parent)
	{
		$this->parent = $parent;
	}
	public function findParent ($class_name)
	{
		$current = $this->getParent();
		while(!is_null($current))
		{
			if($current instanceof $class_name)
				return $current;
			$current = $current->getParent();
		}
		return null;
	}
	protected function checkComponentTag($name)
	{
		if($this->getDOMNode()->tagName != $name)
			throw new runTimeException();
	}
	protected function checkComponentTagAttribute($key, $value)
	{
		if(!$this->getDOMNode()->hasAttribute($key))
			throw new runTimeException();
		if($this->getDOMNode()->getAttribute($key) != $value)
			throw new runTimeException();
	}
}
?>