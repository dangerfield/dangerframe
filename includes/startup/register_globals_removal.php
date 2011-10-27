<?php
	/*
	 * Remember folks, register_globals is evil.
	 * The following un-registers all global variables originating from register_globals
	 * Not required for DangerFrame
	 */
	if (ini_get('register_globals'))
	{
		if (is_array($_REQUEST)) foreach(array_keys($_REQUEST) as $var_to_kill) unset($$var_to_kill);
		if (is_array($_SESSION)) foreach(array_keys($_SESSION) as $var_to_kill) unset($$var_to_kill);
		if (is_array($_SERVER))  foreach(array_keys($_SERVER)  as $var_to_kill) unset($$var_to_kill);
	    unset($var_to_kill);
	}
?>