<?php
class DangerFrame_ValidationErrorFeedback
{
	private $error;
	private $message;
	
	public function __construct(DangerFrame_IValidationError $error, $message)
	{
		$this->error	= $error;
		$this->message	= $message;
	}
	public function getError()
	{
		return $this->error;
	}
	public function getMessage()
	{
		return $this->message;
	}
	public function __toString()
	{
		return $this->message;
	}
}
?>