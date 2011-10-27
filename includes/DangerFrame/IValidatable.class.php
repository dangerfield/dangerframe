<?php
interface DangerFrame_IValidatable
{
	public function error($error);
	public function getValue();
	public function isValid(); 
}