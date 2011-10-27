<?php
class DangerFrame_OutputBuffer
{
	protected $alive;
	protected $level;
	protected $callback;
	public function __construct($callback = null,$chunksize = 0, $erase = true)
	{
		ob_start(array($this,'callback'), $chunksize, $erase);
		$this->setCallback($callback);
		$this->alive = true;
		$this->level = ob_get_level();
	}
	public function clean()
	{
		if(!$this->isLastOB()) return false;
		return ob_clean();
	}
	public function endClean()
	{
		if(!$this->isAlive()) return false;
		$this->endFlushNested();
		return ob_end_clean();
	}
	public function endFlush()
	{
		if(!$this->isAlive()) return false;
		$this->endFlushNested();
		return ob_end_flush();
	}
	public function getClean()
	{
		if(!$this->isAlive()) return false;
		$this->endFlushNested();
		return ob_get_clean();
	}
	public function getContents()
	{
		if(!$this->isLastOB()) return false;
		return ob_get_contents();
	}
	public function getFlush()
	{
		if(!$this->isLastOB()) return false;
		return ob_get_flush();
	}
	public function getLength()
	{
		if(!$this->isLastOB()) return false;
		return ob_get_length();
	}
	public function getLevel()
	{
		return $this->level;
	}
	public function setCallback($callback = null)
	{
		$this->callback = $callback;
	}
	public function callback($buffer, $progress)
	{
		if($progress = PHP_OUTPUT_HANDLER_END)
			$this->alive = false;
		
		if(is_null($this->callback))
			return $buffer;
		else
			call_user_func($this->callback, $buffer, $progress);
	}
	public function isAlive()
	{
		return $this->alive;
	}
	public function isLastOB()
	{
		return ($this->level = ob_get_level());
	}
	protected function endFlushNested()
	{
		if($this->isAlive())
		{
			while(!$this->isLastOB())
				ob_end_flush();
		}
	}
}