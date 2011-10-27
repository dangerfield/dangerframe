<?php
class DangerFrame_List extends ArrayObject implements DangerFrame_iList
{
	public function remove($object)
	{
		foreach($this->getIterator() AS $key => $comparedObject)
			if($object === $comparedObject)
				$this->offsetUnset($key);
	}
	public function indexOf($object)
	{
		foreach($this->getIterator() AS $key => $comparedObject)
			if($object === $comparedObject)
				return $key;
	}
}
?>