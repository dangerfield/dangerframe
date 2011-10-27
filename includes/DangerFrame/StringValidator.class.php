<?php
abstract class DangerFrame_StringValidator extends DangerFrame_AbstractValidator
{
	public static function exactLength($length)
	{
		return new DangerFrame_ExactLengthValidator($length);
	}
	public static function lengthBetween($minimum, $maximum)
	{
		return new DangerFrame_LengthBetweenValidator($minimum, $maximum);
	}
	public static function maximumLength($maximum)
	{
		return new DangerFrame_MaximumLengthValidator(maximum);
	}
	public static function minimumLength($minimum)
	{
		return new DangerFrame_MinimumLengthValidator($minimum);
	}
}