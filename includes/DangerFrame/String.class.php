<?php
class DangerFrame_String
{
	protected $string;
	public function __construct($string)
	{
		$this->string = $string;
	}
	public function __toString()
	{
		return $this->string;
	}
}