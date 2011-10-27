<?php
class DangerFrame_LoopItem extends DangerFrame_WebMarkupContainer
{
	protected $iteration;
	public function __construct($iteration)
	{
		parent::__construct(null);
		$this->setIteration($iteration);
	}
	public function getIteration()
	{
		return $this->iteration;
	}
	protected function setIteration($iteration)
	{
		$this->iteration = $iteration;
	}
}
?>