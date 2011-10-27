<?php
interface DangerFrame_IList extends ArrayAccess, IteratorAggregate, Countable
{
	public function remove($object);
	public function indexOf($object);
}