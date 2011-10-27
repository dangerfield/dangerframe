<?php
class DangerFrame_FormComponent_IsValidVisitor implements DangerFrame_FormComponentIVisitor
{
	protected $valid = true;
	public function formComponent(DangerFrame_IFormVisitorParticipant $formComponent)
	{
		if($formComponent->hasErrorMessage())
		{
			$this->valid = false;
			return DangerFrame_ComponentIVisitor::STOP_TRAVERSAL;
		}
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
	public function isValid()
	{
		return $this->valid;
	}
}
class DangerFrame_FormComponent extends DangerFrame_WebMarkupContainer implements DangerFrame_IFormVisitorParticipant, DangerFrame_IValidatable
{
	const NO_RAW_INPUT = '[-NO-RAW-INPUT-]';
	protected $validators;
	protected $required = false;
	protected $rawInput = self::NO_RAW_INPUT;
	protected $convertedInput;
	private $typeName;
	public function __construct($DFId, DangerFrame_iModel $model = null)
	{
		parent::__construct($DFId, $model);
		$this->validators = new ArrayObject();
		$this->errors = new ArrayObject();
	}
	public function add($object)
	{
		if($object instanceof DangerFrame_Ivalidator )
			$this->validators->append($object);
		else
			parent::add($object);
	}
	public function checkRequired()
	{
		if($this->isRequired())
		{
			$input = $this->getInput();
			
			if(is_null($input) && !$this->isInputNullable() && false)
				return true;
			else
				return strlen($this->getInput()) > 0;
		}
		return true;
	}
	public function clearInput()
	{
		$this->rawInput = self::NO_RAW_INPUT;
	}
	protected function convertInput()
	{
		if(is_null($this->typeName) || $this->isTypePrimative())
		{
			try
			{
				$this->convertedInput = $this->convertValue($this->getInputAsArray());
			}
			catch(DangerFrame_ConversionException $e)
			{
				$error = new DangerFrame_ValidationError();
				$error->addMessageKey('ConversionError');
				$this->reportValidationError($e, $error);
			}
		}
		else
		{
			$converter = $this->getConverter($this->getType());
			try
			{
				
				$this->convertedInput = $converter->convertToObject($this->getInput());
			}
			catch(DangerFrame_ConversionException $e)
			{
				$error = new DangerFrame_ValidationError();
				$error->addMessageKey('ConversionError');
				$this->reportValidationError($e, $error);	
			}
			
		}
	}
	protected function convertValue(array $value)
	{
		return ($value != null && count($value) > 0 && $value[0] != null ? $this->trim($value[0]) : null);
	}
	public function error($error)
	{
		if($error instanceof DangerFrame_IValidationError)
		{
			$source = new DangerFrame_FormComponentMessageSource($this);
			$message = $error->getErrorMessage($source);
			if(is_null($message))
				$this->error(new DangerFrame_ValidationErrorFeedback($error,'Invalid'));
				//throw new RuntimeException('Unexpected missing error message');
			$this->error(new DangerFrame_ValidationErrorFeedback($error, $message));
		}
		else
		{
			parent::error($error);
		}
	}
	public function getConvertedInput()
	{
		return $this->convertedInput;
	}
	public function getForm()
	{
		$form = DangerFrame_Form::findForm($this);
		if(is_null($form))
			throw new RuntimeException;
		return $form;
	}
	public function getInput()
	{
		$value = $this->getInputAsArray();
		return ($value != null && count($value) > 0 && $value[0] != null ? $this->trim($value[0]) : null);
	}
	public function getInputAsArray()
	{
		if(isset($_REQUEST[$this->getInputName()])){
			$values = $_REQUEST[$this->getInputName()];
			if(!is_array($values))
				$values = array($values);
			
				
			return $values;
		}else
			return array('');
			
	}

	public function getInputName()
	{
		return $this->getDOMNode()->getAttribute('name');
	}
	public function getModel()
	{
		return $this->getDefaultModel();
	}
	public function getModelObject()
	{
		return $this->getDefaultModelObject();
	}
	protected function getModelValue()
	{
		return $this->getDefaultModelObjectAsString();
	}
	public function getRawInput()
	{
		return NO_RAW_INPUT == $this->rawInput ? null : $this->rawInput;
	}
	public function getType()
	{
		return $this->typeName;
	}
	public function getValidatorKeyPrefix()
	{
		$form = $this->findParent('DangerFrame_Form');
		if (!is_null($form))
		{
			return $this->getForm()->getValidatorKeyPrefix();
		}
		return null;
	}
	public function getValidators()
	{
		return $this->validators;
	}
	public function getValue()
	{
		if($this->rawInput == self::NO_RAW_INPUT)
			return $this->getModelValue();
		else
			return $this->rawInput;
	}
	public function hasRawInput()
	{
		return self::NO_RAW_INPUT != $this->rawInput;
	}
	//protected function inputAsInt() {}
	//protected function inputAsInt($defaultValue) {}
	//protected function inputAsIntArray() {}
	public function inputChanged()
	{
		$input = $this->getInputAsArray();
		if(!is_null($input) && count($input) > 0 && !is_null($input[0]))
			$this->rawInput = implode(';', $input);
		else if($this->isInputNullable())
			$this->rawInput = null;
		else
			$this->rawInput = self::NO_RAW_INPUT;
	}
	protected function internalOnModelChanged()
	{
		$this->valid();
	}
	public function invalid()
	{
		$this->onInvalid();
	}
	public function isInputNullable()
	{
		return true;
	}
	//public function isMultiPart()
	//{
	//	return false;
	//}
	//public function isPersistent() {}
	public function isRequired()
	{
		return $this->required;
	}
	public function isTypePrimative()
	{
		return !class_exists($this->getType());
	}
	public function isValid()
	{
		$temp = new DangerFrame_FormComponent_IsValidVisitor();
		self::visitFormComponentsPostOrder($this, $temp);
		return $temp->isValid();
	}
	public function newValidatable() {}

	protected function onDisabled()
	{
		$this->getDOMNode()->setAttribute('disabled','disabled');
	}
	protected function onInvalid() {}
	protected function onValid() {}
	public function processChildren()
	{
		return true;
	}
	public function processInput()
	{
		$this->inputChanged();
						
		$this->validate();
		if($this->hasErrorMessage())
			$this->invalid();
		else
		{
			$this->valid();
			$this->updateModel();
		}
	}
	private function reportValidationError(DangerFrame_ConversionException $e, DangerFrame_ValidationError $error)
	{
		$this->error($error);
	}
	public function setConvertedInput($convertedInput)
	{
		$this->convertedInput = $convertedInput;
	}
	//public function setLabel(IModel<java.lang.String> labelModel) {}
	public function setModel($model) //DangerFrame_iModel 
	{
		$this->setDefaultModel($model);
	}
	public function setModelObject($object)
	{
		$this->setDefaultModelObject($object);
	}
	public function setModelValue(string $value)
	{
		$this->convertedInput = $this->convertValue($value);
		$this->updateModel();
	}
	//public function setPersistent(boolean persistent) {}

	private function reportRequiredError()
	{
		$error = new DangerFrame_ValidationError;
		$error->addMessageKey('Required');
		$this->error($error);
	}
	public function setRequired($required)
	{
		$this->required = (bool) $required;
	}
	

	public function setType($type = null)
	{
		$this->typeName = $type;
		//if(in_array($type,array('int','bool','float','double','string')))
		//	$this->setRequired(true);
	}
	
	protected function shouldTrimInput()
	{
		return true;
	}
	//protected function supportsPersistence() {}
	protected function trim($string)
	{
		if($this->shouldTrimInput())
			return trim($string);
		else
			return $string;
	}
	public function updateModel()
	{
		$this->setModelObject($this->getConvertedInput());
	}
	public function valid()
	{
		$this->clearInput();
		$this->onValid();
	}
	public function validate()
	{
		$this->validateRequired();
		if($this->isValid())
		{
			$this->convertInput();
			if($this->isValid())
			{
				if($this->isRequired() && is_null($this->getConvertedInput()) && $this->isInputNullable())
					$this->reportRequiredError();
				else
					$this->validateValidators();
			}	
		}
	}
	protected function validateRequired()
	{
		if(!$this->checkRequired())
			$this->reportRequiredError();
	}
	protected function validateValidators()
	{
		foreach( $this->getValidators() AS $validator )
		{
			$validator->validate($this);
			if(!$this->isValid())
				break;
		}
	}

	public static function visitComponentsPostOrder($component,$visitor)
	{
		self::visitComponentsPostOrderHelper($component, $visitor);
	}
	private static function visitComponentsPostOrderHelper($component,$visitor)
	{
		if($component instanceof DangerFrame_MarkupContainer)
		{
			if($component->size() > 0)
			{
				$visitChildren = true;
				if($component instanceof DangerFrame_IFormVisitorParticipant)
					$visitChildren = $component->processChildren();
				if($visitChildren)
				{
					$children = $component->getIterator();
					foreach($children AS $child)
					{
						$value = self::visitFormComponentsPostOrder($child, $visitor);
						if($value == DangerFrame_ComponentIVisitor::STOP_TRAVERSAL)
							return $value;
						else if($value == DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL
							|| $value == DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER)
						{} //noop
						else
							return $value;
					}
				}
			}
		}
	} //DangerFrame_Component  DangerFrame_FormComponentIVisitor 
	public static function visitFormComponentsPostOrder($component,$visitor)
	{
		self::visitFormComponentsPostOrderHelper($component, $visitor);
	}
	private static function visitFormComponentsPostOrderHelper($component,$visitor)
	{
		if($component instanceof DangerFrame_MarkupContainer)
		{
			if($component->size() > 0)
			{
				$visitChildren = true;
				if($component instanceof DangerFrame_IFormVisitorParticipant)
					$visitChildren = $component->processChildren();
				if($visitChildren)
				{
					$children = $component->getIterator();
					foreach($children AS $child)
					{
						$value = self::visitFormComponentsPostOrder($child, $visitor);
						if($value == DangerFrame_ComponentIVisitor::STOP_TRAVERSAL)
							return $value;
					}
				}
			}
		}
		if($component instanceof DangerFrame_FormComponent)
			return $visitor->formComponent($component);
		return null;
	}
	public function render()
	{
		$this->getDOMNode()->setAttribute('name', $this->getInputName());
	}
	
}