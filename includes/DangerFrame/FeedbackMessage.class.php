<?php
class DangerFrame_FeedbackMessage
{
	const DEBUG = 100;
	const ERROR = 400;
	const FATAL = 500;
	const INFO = 200;
	const UNDEFINED = 0;
	const WARNING = 300;
	
	private static function matchToString($level)
	{
		switch($level)
		{
			case self::DEBUG: return 'DEBUG';
			case self::ERROR: return 'ERROR';
			case self::FATAL: return 'FATAL';
			case self::INFO: return 'INFO';
			case self::UNDEFINED: return 'UNDEFINED';
			case self::WARNING: return 'WARNING';
		}
	}
	
	private $level;
	private $message;
	private $reporter;
	private $rendered = false;
	
	public function __construct(DangerFrame_Component $reporter, $message, $level)
	{
		$this->reporter = $reporter;
		$this->message = $message;
		$this->level = $level;
	}
	public function isRendered()
	{
		return $this->rendered;
	}
	public function markRendered()
	{
		$this->rendered = true;
	}
	public function getLevel()
	{
		return $this->level;
	}
	public function getLevelAsString()
	{
		return self::matchToString($this->getLevel());
	}
	public function getMessage()
	{
		return $this->message;
	}
	public function getReporter()
	{
		return $this->reporter;
	}
	public function isDebug()
	{
		return $this->isLevel(self::DEBUG);	
	}
	public function isError()
	{
		return $this->isLevel(self::ERROR);
	}
	public function isFatal()
	{
		return $this->isLevel(self::FATAL);
	}
	public function isInfo()
	{
		return $this->isLevel(self::INFO);
	}
	public function isLevel($level)
	{
		return $this->getLevel() >= $level;
	}
	public function isUndefined()
	{
		return $this->getLevel == self::UNDEFINED;		
	}
	public function isWarning()
	{
		return $this->isLevel(self::WARNING);
	}
	public function __toString()
	{
		return "[FeedbackMessage message = \"". $this->getMessage() . "\", reporter = " .
			(($this->getReporter() == null) ? "null" : $this->getReporter()->getId()) . ", level = " .
			$this->getLevelAsString() . "]";		
	}
	public function detach()
	{
		$this->reporter = null;
	}
}