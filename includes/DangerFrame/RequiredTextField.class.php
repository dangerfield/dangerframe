<?php
class DangerFrame_RequiredTextField extends DangerFrame_TextField
{
	public function __construct($DFId, DangerFrame_iModel $model = null)
	{
		parent::__construct($DFId, $model);
		$this->setRequired(true);
	}
}