<?php
abstract class DangerFrame_Loop extends DangerFrame_AbstractRepeater
{
	protected $iterations = 0;
	public function __construct($DFId, $iterations)
	{
		parent::__construct($DFId);
		$this->setIterations($iterations);
	}
	public function getIterations()
	{
		return $this->iterations;
	}
	protected function setIterations($iterations)
	{
		$this->iterations = $iterations;
	}
	protected function newItem($iteration)
	{
		return new DangerFrame_LoopItem($iteration);
	}
	protected function onPopulate()
	{
		for($i=0; $i<$this->iterations; $i++)
		{
			$item = $this->newItem($i);
			$this->add($item);
			$this->populateItem($item);
		}	
	}
	abstract protected function populateItem(DangerFrame_LoopItem $item);
	
	protected function renderItem(DangerFrame_LoopItem $item){}
	protected function renderChild(DangerFrame_Component $child)
	{
		$this->renderItem($child);
		$child->render();
	}
	protected function renderIterator()
	{
		return $this->subComponents->getIterator();
	}
	
}