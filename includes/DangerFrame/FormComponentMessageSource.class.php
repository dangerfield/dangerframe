<?php
class DangerFrame_FormComponentMessageSource implements DangerFrame_IErrorMessageSource
{
	protected $formComponent;
	public function __construct(DangerFrame_FormComponent $component)
	{
		$this->formComponent = $component;
		$prefix = $this->formComponent->getValidatorKeyPrefix();
		$message = null;
	}
	public function getMessage($key)
	{}
	public function substitute($string, ArrayObject $vars)
	{}
	
}