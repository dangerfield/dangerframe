<?php
	define('SITE_ROOT_DIR',  realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR);
	define('CONFIG_DIR', SITE_ROOT_DIR . 'configuration' . DIRECTORY_SEPARATOR);
	define('INCLUDE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
	foreach(glob(INCLUDE_DIR . DIRECTORY_SEPARATOR . 'startup' . DIRECTORY_SEPARATOR . '*.php') AS $file)
		require_once($file);
?>
