<?php
abstract class DangerFrame_AbstractValidator implements DangerFrame_INullAcceptingValidator
{
	public function validateOnNullValue()
	{
		return false;
	}
	protected abstract function onValidate(DangerFrame_IValidatable $validatable);
	public function validate(DangerFrame_IValidatable $validatable)
	{
		if ( !is_null($validatable->getValue()) || $this->validateOnNullValue())
		{
			$this->onValidate($validatable);
		}
	}
	
	public function error(DangerFrame_IValidatable $validatable)
	{
		$args = func_get_args();
		switch(func_num_args())
		{
			case 1:
				$this->errorA($validatable);
				break;
			case 2:
				if($args[1] instanceof ArrayObject)
					$this->errorC($validatable, $args[1]);
				else
					$this->errorB($validatable, $args[1]);
				break;
			case 3:
				$this->errorD($validatable, $args[1], $args[2]);
				break;
		}
	}
	public function errorA(DangerFrame_IValidatable $validatable)
	{
		$this->error($validatable, $this->resourceKey(), $this->variablesMap($validatable));
	}
	public function errorB(DangerFrame_IValidatable $validatable, $resourceKey = null)
	{
		if (is_null($resourceKey))
		{
			throw new RuntimeException("Argument [[resourceKey]] cannot be null");
		}
		$this->error($validatable, $resourceKey, $this->variablesMap($validatable));
	}
	public function errorC(DangerFrame_IValidatable $validatable, ArrayObject $vars)
	{
		if (is_null($vars))
		{
			throw new RuntimeException("Argument [[vars]] cannot be null");
		}
		$this->error(validatable, resourceKey(), vars);
	}
	
	public function errorD(DangerFrame_IValidatable $validatable, $resourceKey, ArrayObject $vars)
	{
		if (is_null($validatable))
		{
			throw new RuntimeException("Argument [[validatable]] cannot be null");
		}
		if (is_null($vars))
		{
			throw new RuntimeException("Argument [[vars]] cannot be null");
		}
		if (is_null($resourceKey))
		{
			throw new RuntimeException("Argument [[resourceKey]] cannot be null");
		}


		$error = new DangerFrame_ValidationError();
		$error = $error->addMessageKey($resourceKey);
		$defaultKey = get_class($this);
		if (!$resourceKey === $defaultKey)
			$error->addMessageKey($defaultKey);

		$error->setVariables($vars);
		$validatable->error($error);
	}
	protected function resourceKey()
	{
		return get_class($this);
	}
	protected function variablesMap(DangerFrame_IValidatable $validatable)
	{
		return new ArrayObject();
	}
	
}