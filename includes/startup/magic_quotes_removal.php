<?php
	/*
	 * Magic quotes is horrible, evil and useless.
	 * The following de-slashes slashes added by magic_quotes if it's enabled.
	 * Not required for DangerFrame. All inputs to DangerFrame are usumed to be slashless however.
	 */
	if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())
	{
	    function stripslashes_deep($value)
	    {
	        $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	        return $value;
	    }
	
	    $_POST		= array_map('stripslashes_deep', $_POST);
	    $_GET		= array_map('stripslashes_deep', $_GET);
	    $_COOKIE	= array_map('stripslashes_deep', $_COOKIE);
	    $_REQUEST	= array_map('stripslashes_deep', $_REQUEST);
	}
?>