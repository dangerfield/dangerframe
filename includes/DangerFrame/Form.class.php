<?php
class DangerFrame_Form_ClearInput extends DangerFrame_FormComponentAbstractVisitor
{
	protected function onFormComponent(DangerFrame_FormComponent $formComponent)
	{
		$formComponent->clearInput();
	}
}
class DangerFrame_Form_VisitFormComponents implements DangerFrame_ComponentIVisitor
{
	protected $visitor;
	public function __construct(DangerFrame_ComponentIVisitor $visitor)
	{
		$this->visitor = $visitor;
	}
	public function component(DangerFrame_Component $component)
	{
		$visitor->formComponent($component);
	}
}
class DangerFrame_Form_DelegateSubmit implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		if(!$component instanceof DangerFrame_Form)
			throw new RuntimeException();

		$component->onSubmit();
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_MarkFormsSubmitted implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		$component->submitted = true;
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_FindFormVisitor implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		return DangerFrame_ComponentIVisitor::STOP_TRAVERSAL;
	}
}
class DangerFrame_Form_FindSubmittingButton implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		$form = $component->getForm();
		if(!is_null($form) && $form->getRootForm() == $form)
		{
			$name = $component->getInputName();
			if(isset($_REQUEST[$name]))
				return $component;
		}
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
}
abstract class DangerFrame_Form_ValidationVisitor implements DangerFrame_FormComponentIVisitor
{
	public function formComponent(DangerFrame_IFormVisitorParticipant $component)
	{
		if($component instanceof DangerFrame_FormComponent)
		{
			if($component->isValid())
				$this->validate($component);
		}
		if($component->processChildren())
			return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
		else
			return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER;
	}
	public abstract function validate(DangerFrame_FormComponent $formComponent);
}

class DangerFrame_Form_ValidateComponents extends DangerFrame_Form_ValidationVisitor
{
	public function validate(DangerFrame_FormComponent $formComponent)
	{
		$formComponent->validate();
	}
}
class DangerFrame_Form_MarkFormComponentsInvalid extends DangerFrame_FormComponentAbstractVisitor
{
	public function onFormComponent(DangerFrame_FormComponent $formComponent)
	{
		$formComponent->invalid();
	}
}
class DangerFrame_FormComponent_InternalMarkFormComponentsValid extends DangerFrame_FormComponentAbstractVisitor
{
	public function onFormComponent(DangerFrame_FormComponent $formComponent)
	{
		$formComponent->valid();
	}
}
class DangerFrame_Form_MarkNestedFormComponentsValid implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		$component->internalMarkFormComponentsValid();
		return self::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_AnyFormComponentError_A implements DangerFrame_ComponentIVisitor
{
	protected $error = false;
	public function component(DangerFrame_Component $component)
	{
		if($component->hasErrorMessage())
		{
			$this->error = true;
			return self::STOP_TRAVERSAL;
		}
		return self::CONTINUE_TRAVERSAL;
	}
	public function hasError()
	{
		return $this->error;
	}
}
class DangerFrame_Form_AnyFormComponentError_B implements DangerFrame_ComponentIVisitor
{
	private $visitor;
	public function __construct(DangerFrame_ComponentIVisitor $visitor)
	{
		$this->visitor = $visitor;
	}
	public function component(DangerFrame_Component $component)
	{
		if (($component instanceof DangerFrame_Form) || ($component instanceof DangerFrame_FormComponent))
			return $this->visitor->component($component);
		return self::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_CallOnError implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		if($component->hasError())
			$component->onError();
		return self::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_ValidateNestedForms implements DangerFrame_ComponentIVisitor
{
	public function component(DangerFrame_Component $component)
	{
		$component->validateComponents();
		$component->validateFormValidators();
		$component->onValidate();
		return self::CONTINUE_TRAVERSAL;
	}
}
class DangerFrame_Form_InputChanged extends DangerFrame_FormComponentAbstractVisitor
{
	public function onFormComponent(DangerFrame_FormComponent $formComponent)
	{
		$formComponent->inputChanged();
	}
}
class DangerFrame_Form extends DangerFrame_MarkupContainer
{
	const METHOD_GET	= 1;
	const METHOD_POST	= 2;
	
	protected $validators;
	protected $submitted = false;
	public function __construct($DFId, DangerFrame_iModel $iModel = null)
	{
		parent::__construct($DFId, $iModel);
		$this->validators = new DangerFrame_List();
	}
	public function add($object)
	{
		if(!$object instanceof DangerFrame_Component)
			throw new RuntimeException();
		if(func_num_args() > 1)
			foreach(func_get_args() AS $arg)
				$this->add($arg);
		else
		{	
			if($object instanceof DangerFrame_IFormValidator)
				$this->validators->append($object);
			else
				parent::add($object);
			return $object;
		}
	}
	private function anyFormComponentError()
	{
		$visitor = new DangerFrame_Form_AnyFormComponentError_A();
		$this->visitChildren(new DangerFrame_Form_AnyFormComponentError_B($visitor), 'DangerFrame_Component');
		return $visitor->hasError();
	}
	protected function beforeUpdateFormComponentModels() {}
	private function callOnError()
	{
		$this->onError();
		$this->visitChildren(new DangerFrame_Form_CallOnError, 'DangerFrame_Form');
	}
	public function clearInput()
	{
		$this->visitFormComponentsPostOrder(new DangerFrame_Form_ClearInput());
	}
	protected function delegateSubmit(IFormSubmittingComponent $submittingComponent = null)
	{
		$formToProcess = $this;
		if(!is_null($submittingComponent))
		{
			$formToProcess = $submittingComponent->getForm();
			$submittingComponent->onSubmit();
		}
		$formToProcess->onSubmit();
		if(!is_null($submittingComponent))
			$formToProcess->visitChildren(new DangerFrame_Form_DelegateSubmit());
	}
	protected function encodeUrlInHiddenFields() {}
	public function error($error, ArrayObject $errors = null)
	{
		parent::error($error); //IGNORE VARS
	}
	static function findForm(DangerFrame_Component $component)
	{
		$form = $component->findParent('DangerFrame_Form');
		if(is_null($form))
		{
			//TODO When borders are added.	
		}
		return $form;
	}
	public function findSubmittingButton()
	{
		return null;
		return $this->getPage()->visitChildren(new DangerFrame_Form_FindSubmittingButton(), 'IFormSubmittingComponent');
	}
	public function getDefaultButton()
	{
		if($this->isRootForm())
			return $this->defaultSubmittingComponent;
		else
			return $this->getRootForm()->getDefaultButton();
	}
	public function getFormValidators()
	{
		return $this->validators;
	}
	//protected function getHiddenFieldId() {}
	//protected function getInputNamePrefix() {}
	public static function getMaxSize()
	{
		return ini_get('upload_max_filesize');
	}
	protected function getMethod()
	{
		$attribute = $this->getDOMNode()->getAttribute('method');
		switch( strtoupper( $attribute ) )
		{
			case 'GET':
			case 'POST':
				return strtoupper( $attribute );
			default: 
				return 'GET';
		}
	}
	public function getModel()
	{
		return $this->getDefaultModel();
	}
	public function getModelObject()
	{
		return $this->getDefaultModelObject();
	}
	public function getRootForm()
	{
		$parent = $this;
		do
		{
			$form = $parent;
			$parent = $form->findParent('DangerFrame_Form');
		}
		while($parent != null);
		
		return $form;
	}
//	protected function getStatelessHint() {}
	public function getValidatorKeyPrefix() {}
//	public function getValuePersister() {}

	public function hasError()
	{
		if($this->hasErrorMessage())
			return true;
		return $this->anyFormComponentError();	
	}
	private function internalMarkFormComponentsValid()
	{
		$this->visitFormComponentsPostOrder(new DangerFrame_FormComponent_InternalMarkFormComponentsValid());
	}
	private function inputChanged()
	{
		$this->visitFormComponentsPostOrder(new DangerFrame_Form_InputChanged);
	}
	//protected function internalOnModelChanged() {}
	public function isRootForm()
	{
		return is_null($this->findParent('Form'));
	}
	public function isSubmitted()
	{
		return $this->submitted;
	}
	//public function isVersioned() {}
	//public function loadPersistentFormComponentValues() {}
	protected function markFormComponentsInvalid()
	{
		$this->visitFormComponentsPostOrder(new DangerFrame_Form_MarkFormComponentsInvalid());
	}
	protected function markFormComponentsValid()
	{
		$this->internalMarkFormComponentsValid();
		$this->markNestedFormComponentsValid();
	}
	private function markFormsSubmitted()
	{
		$this->submitted = true;
		$this->visitChildren(new DangerFrame_Form_MarkFormsSubmitted(), 'DangerFrame_Form');
	}
	private function markNestedFormComponentsValid()
	{
		$this->visitChildren(new DangerFrame_Form_MarkNestedFormComponentsValid(),'DangerFrame_Form');
	}
	protected function onError() {}
	protected function onFileUploadException(FileUploadExceptione $error, $errors) {}
	public function onFormSubmitted()
	{
		$this->markFormsSubmitted();
		$this->inputChanged();
		$submittingComponent = $this->findSubmittingButton();
		if(!is_null($submittingComponent) && !$submittingComponent->getDefaultFormProcessing())
			$submittingComponent->onSubmit();
		else
			$formToProcess = $this;
			if(!is_null($submittingComponent))
				$formToProcess = $submittingComponent->getForm();
			$formToProcess->process($submittingComponent);
	}

	protected function onSubmit() {}
	protected function onValidate() {}
	public function process(DangerFrame_IFormSubmittingComponent $submittingComponent = null)
	{
		if ($this->furtherProcess())
			$this->delegateSubmit($submittingComponent);
	}
	public function furtherProcess()
	{
		$this->validate();
		if($this->hasError())
		{
			$this->markFormComponentsInvalid();
			$this->callOnError();
			return false;
		}
		else
		{
			$this->markFormComponentsValid();
			$this->beforeUpdateFormComponentModels();
			$this->updateFormComponentModels();
		//	$this->persistFormComponentData();
			return true;
		}
	}
	public function remove($validator = null)
	{
		if($object instanceof IFormValidatorvalidator)
			$this->validators->remove($object);
		else
			parent::remove($object);
	}
//	public function removePersistentFormComponentValues($disablePersistence) {}
//	protected function renderPlaceholderTag(ComponentTag $tag, Response $response) {}
	public function setDefaultButton(IFormSubmittingComponent $submittingComponent)
	{
		if($this->isRootForm())
			$this->defaultSubmittingComponent = $submittingComponent;
		else
			$this->getRootForm()->setDefaultButton($submittingComponent);
	}
	public static function setMaxSize($size) 
	{
		ini_set('upload_max_filesize', $size);
	}
	public function setModel(DangerFrame_IModel $model)
	{
		$this->setDefaultModel($model);
	}
	public function setModelObject($object)
	{
		$this->setDefaultModelObject($object);
	}
	public function setMultiPart($multiPart)
	{
		if($multiPart)
			$this->getDomNode()->setAttribute('enctype','multipart/form-data');
		else
			$this->getDomNode()->removeAttribute('enctype');
	}
	//public function setVersioned($isVersioned) {}
	public function visitFormComponents(DangerFrame_FormComponentIVisitor $visitor)
	{
		$this->visitChildren(new DangerFrame_Form_VisitFormComponents($visitor));
	}
	public function visitFormComponentsPostOrder(DangerFrame_FormComponentIVisitor $visitor)
	{
		DangerFrame_FormComponent::visitFormComponentsPostOrder($this, $visitor);
	}
	protected function updateFormComponentModels()
	{
	 	foreach($this->subComponents AS $component)
	 	{
	 		$component->updateModel();
	 	}
	}
	protected function validate()
	{
		$this->validateComponents();
		$this->validateFormValidators();
		$this->onValidate();
		$this->validateNestedForms();
	}
	protected function validateComponents()
	{
		$this->visitFormComponentsPostOrder(new DangerFrame_Form_ValidateComponents());
	}
	protected function validateFormValidator(IFormValidator $validator)
	{
		$validator->validate($this);
	}
	protected function validateFormValidators()
	{
		foreach( $this->getFormValidators() AS $validator )
		{
			$validator->validate($this);
		}
	}
	private function validateNestedForms()
	{
		$this->visitChildren(new DangerFrame_Form_ValidateNestedForms(),'DangerFrame_Form');
	}
	protected function writeParamsAsHiddenFields(array $params)
	{
		foreach($params as $name => $value)
		{
			$input = DangerFrame_Builder::getDOMDocument()->createElement('input');
			$input->setAttribute('type','hidden');
			$input->setAttribute('name', $name);
			$input->setAttribute('value', $value);
			$this->getDOMNode()->appendChild($input);
		}
		
	}
	public function render()
	{
		if(count($_POST)>0) //hack
		$this->onFormSubmitted();
		parent::render();
	}
}