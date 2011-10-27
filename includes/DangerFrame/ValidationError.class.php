<?php
class DangerFrame_ValidationError implements DangerFrame_IValidationError
{
	private $keys;
	private $vars;
	
	private $message;
	
	public function __construct()
	{
		$this->keys = new ArrayObject;
		$this->vars = new ArrayObject;
	}
	public function addMessageKey($key)
	{
		if (is_null($key) || strlen(trim($key)) == 0)
		{
			throw new RunTimeException;
		}
		$this->keys->append($key);
		return $this;
	}
	public function getErrorMessage(DangerFrame_IErrorMessageSource $messageSource)
	{
		$errorMessage = null;
		foreach($this->keys AS $key)
		{
			$errorMessage = $messageSource->getMessage($key);
			if(!is_null($errorMessage))
				break;
		}
		if(is_null($errorMessage) && !is_null($this->message))
			$errorMessage = $this->message;
		if(!is_null($errorMessage))
		{
			$p = (!is_null($this->vars) ? $this->vars : new ArrayObject);
			$this->errorMessage = $messageSource->substitute($errorMessage, $p);
		}
		return $errorMessage;	
	}
	public function getMessage()
	{
		return $this->message;
	}
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}
	public function getKeys()
	{
		if(is_null($this->keys))
			return new ArrayObject();
		else
			return $this->keys;
	}
	public function setVariables(){}
}