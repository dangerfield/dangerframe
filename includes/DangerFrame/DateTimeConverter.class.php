<?php
class DangerFrame_DateTimeConverter implements DangerFrame_IConverter
{
	public function convertToObject($string)
	{
		return new DateTime($string);
	}
	public function convertToString($dateTimeObject)
	{
		return $dateTimeObject->format(DATE_ATOM);
	}
}