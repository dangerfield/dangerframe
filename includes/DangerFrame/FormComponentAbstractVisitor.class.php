<?php
abstract class DangerFrame_FormComponentAbstractVisitor implements DangerFrame_FormComponentIVisitor
{
	public function formComponent(DangerFrame_IFormVisitorParticipant $component)
	{
		if ($component instanceof DangerFrame_FormComponent)
		{
			$this->onFormComponent($component);
		}
		return DangerFrame_ComponentIVisitor::CONTINUE_TRAVERSAL;
	}
	abstract protected function onFormComponent(DangerFrame_FormComponent $component);
}