<?php
	/*
	 * Mimic's php.ini settings file to change runtime PHP settings to your preferances.
	 * Not required for DangerFrame
	 */
	if(file_exists(CONFIG_DIR . DIRECTORY_SEPARATOR . 'php.ini'))
	if($settings = parse_ini_file(CONFIG_DIR . DIRECTORY_SEPARATOR . 'php.ini', FALSE))
		foreach($settings AS $setting_name => $setting_value)
			ini_set($setting_name,$setting_value);