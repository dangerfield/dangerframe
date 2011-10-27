<?php
interface DangerFrame_IFormValidator
{
	public function getDependentFormComponents();
	public function validate(DangerFrame_Form $form);
}