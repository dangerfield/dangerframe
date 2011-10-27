<?php
class DangerFrame_WildcardListModel extends DangerFrame_GenericBaseModel
{
	public function __construct(DangerFrame_List $object = null)
	{
		if(!is_null($object))
			$this->setObject($object);
	}
}