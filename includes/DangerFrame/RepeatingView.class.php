<?php
class DangerFrame_RepeatingView extends DangerFrame_AbstractRepeater
{
	protected $count = 0;
	protected function newChildId()
	{
		return $this->count++;
	}
	protected function onPopulate(){}
	protected function renderIterator()
	{
		return $this->subComponents->getIterator();
	}
}