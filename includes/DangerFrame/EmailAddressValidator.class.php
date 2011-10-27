<?php
class DangerFrame_EmailAddressValidator extends DangerFrame_PatternValidator
{
	public function __construct()
	{
		parent::__construct('/^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9-]+)*((\\.[A-Za-z]{2,}){1}$)/i');
	}
}