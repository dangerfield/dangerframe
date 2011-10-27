<?php
class DangerFrame_PatternValidator extends DangerFrame_StringValidator
{
	private $pattern;
	private $reverse = false;
	
	public function __construct($pattern)
	{
		$this->pattern = $pattern;
	}
	public function setReverse($reverse)
	{
		$this->reverse = (bool) $reverse;
	}
	protected function onValidate(DangerFrame_IValidatable $validatable)
	{
		// Check value against pattern
		if (!preg_match($this->pattern, $validatable->getValue()))
		{
			$this->error($validatable);
		}
	}
}