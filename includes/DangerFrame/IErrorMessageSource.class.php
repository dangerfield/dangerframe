<?php
interface DangerFrame_IErrorMessageSource
{
	public function getMessage($key);
	public function substitute($string, ArrayObject $vars);
}