<?php
/*
 * The following is vastly different from Wicket, as PHP has useful functions leaving out many lines!
 * Unfortuantly also, a property cannot be created if it is missing (like in Wicket),
 * because we can't specify it's type in it's parent model!
 */
class DangerFrame_PropertyResolver
{
	public static function getPropertyClass($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression)->getPropertyClass();
	}
	public static function getPropertyField($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression)->getPropertyField();
	}
	public static function getPropertyGetter($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression)->getPropertyGetter();
	}
	public static function getPropertySetter($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression)->getPropertySetter();
	}
	public static function getValue($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression)->getValue();
	}
	public static function getResolvedPropertyObject($expression, $object)
	{
		return DangerFrame_ResolvedProperty::staticResolve($object, $expression);
	}
	
	
	
}

class DangerFrame_ResolvedProperty
{
	protected $model;
	protected $expression;
	protected $key;
	
	/*
	 * Factory methods
	 */

	public static function staticResolve($model, $expression)
	{
		if(is_null($expression) || strlen($expression))
			new RuntimeException('Expression may not be empty');
			
		$temp =	self::resolveDot($model, $expression);
		if(!is_null($temp))
			return $temp;
		$temp = self::resolveBracket($model, $expression);
		if(!is_null($temp))
			return $temp;
		
		return new DangerFrame_ResolvedProperty($model, $expression);
	}

	protected static function resolveDot($model, $expression)
	{
		$temp = explode('->', $expression, 2);
		if(count($temp) > 1)
		{
			$property = new DangerFrame_ResolvedProperty($model, $temp[0]);
			return $property->resolve($temp[1]);
		}
		return null;
	}
	protected static function resolveBracket($model, $expression)
	{
		if(preg_match('/^([a-z0-9]+)\[([0-9a-z]+)\](?:->(.+))?$/i', $expression, $matches))
		{
			$property = new DangerFrame_ResolvedArrayElementProperty($model, $matches[1], $matches[2]);
			if(count($matches) == 5)
				return $property->resolve($matches[3]);
			else
				return $property;
		}
		return null;
	}

	public function resolve($expression)
	{
		return self::staticResolve($this->getValue(), $expression);
	}
	

	public function __construct($model, $expression)
	{
		$this->model = $model;
		$this->expression = $expression;
		
		try
		{	
			$this->getPropertyReflection();
		}
		catch(ReflectionException $e)
		{
			throw new RuntimeException();
		}
	}


	public function getPropertyGetter()
	{
		if($this->isPropertyGetterMethodPublic())
			return new DangerFrame_ReflectMethodInvoker($this->getGetterMethodReflection(), $this->model);
		else if($this->isPropertyPublic())
			return $this->createPropertyGetter();
		else
			throw new RuntimeException();
	}
	public function getPropertySetter()
	{
		if($this->isPropertyGetterMethodPublic())
			return new DangerFrame_ReflectMethodInvoker($this->getSetterMethodReflection(), $this->model);
		else if($this->isPropertyPublic())
			return $this->createPropertySetter();
		else
			throw new RuntimeException();
	}
	
	private function createPropertyGetter()
	{
		return new DangerFrame_Getter($this->getPropertyReflection(), $this->model);
	}
	private function createPropertySetter()
	{
		return new DangerFrame_Setter($this->getPropertyReflection(), $this->model);
	}
	private function isPropertyPublic()
	{
		return $this->getPropertyReflection()->isPublic();
	}
	private function isPropertyGetterMethodPublic()
	{
		return $this->isPropertyGetterMethod() && $this->getGetterMethodReflection()->isPublic();
	}
	private function isPropertySetterMethodPublic()
	{
		return $this->isPropertySetterMethod() && $this->getSetterMethodReflection()->isPublic();
	}
	private function isPropertyGetterMethod()
	{
		return !is_null($this->getGetterMethodReflection());
	}
	private function isPropertySetterMethod()
	{
		return !is_null($this->getSetterMethodReflection());
	}
	private function getModelReflection()
	{
		return new ReflectionClass(get_class($this->model));
	}
	private function getPropertyReflection()
	{
		return $this->getModelReflection()->getProperty($this->expression);
	}
	private function getSetterMethodReflection()
	{
		try
		{
			return $this->getModelReflection()->getMethod('set' . ucfirst($this->expression));
		}
		catch(ReflectionException $e)
		{
			return null;
		}
	}
	private function getGetterMethodReflection()
	{
		try
		{
			return $this->getModelReflection()->getMethod('get' . ucfirst($this->expression));
		}
		catch(ReflectionException $e)
		{
			return null;
		}
	}
	
	public function getPropertyClass()
	{
		$thing = $this->getValue();
		if(is_object($thing))
			return get_class();
		else
			return gettype($thing);
	}
	public function getValue()
	{
	 	return $this->getPropertyGetter()->invoke();
	}
	public function setValue($object)
	{
		return $this->getPropertySetter()->invoke($object);
	}
}

class DangerFrame_ResolvedArrayElementProperty extends DangerFrame_ResolvedProperty
{
	protected $key;
	public function __construct($model, $attribute, $key)
	{
		parent::__construct($model, $attribute);
		$this->key = $key;
		if(!$this->isList())
			throw new RuntimeException;
	}
	private function isList()
	{
		return $this->getListAttribute instanceof ArrayAccess || is_array($this->getListAttribute);
	}
	public function getListAttribute()
	{
		
	}
}

class DangerFrame_ReflectMethodInvoker
{
	protected $method;
	protected $object;
	public function __construct(ReflectionMethod $method, $object)
	{
		$this->method = $method;
		$this->object = $object;
	}
	public function invoke()
	{
		if(func_num_args() > 0)
		{
			$args = func_get_args();
			return $this->method->invokeArgs($this->object, $args);
		}
		return $this->method->invoke($this->object);
	}
}

class DangerFrame_Getter
{
	protected $property;
	protected $object;
	public function __construct(ReflectionProperty $property, $object)
	{
		$this->property	= $property;
		$this->object	= $object;
	}
	public function invoke()
	{
		return $this->property->getValue($this->object);
	}
}

class DangerFrame_Setter
{
	protected $property;
	protected $object;
	public function __construct(ReflectionProperty $property, $object)
	{
		$this->property	= $property;
		$this->object	= $object;
	}
	public function invoke($value)
	{
		$this->property->setValue($this->object, $value);
	}
}
?>