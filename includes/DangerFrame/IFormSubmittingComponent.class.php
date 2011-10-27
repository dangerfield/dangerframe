<?php
interface DangerFrame_IFormSubmittingComponent
{
		public function getDefaultFormProcessing();
		public function getForm();
		public function getInputName();
		public function onSubmit();
}