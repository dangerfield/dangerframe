<?php
	function DangerFrame_AutoLoad($class_name)
	{
		$hierarchy = explode("_",$class_name);
		
		if(array_shift($hierarchy) != "DangerFrame") return false;
		
		$filepath = dirname(__FILE__) . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $hierarchy) . ".class.php";
			
		if(!file_exists($filepath))
			throw new exception('Failed to find '.$class_name.' at '.$filepath);
		
		require($filepath);
		
		if (!class_exists($class_name, FALSE) && !interface_exists($class_name, FALSE))
			throw new exception('Unable to load class: '.$class_name.'. Check class name & filename match. Check namespace.');
		
		return true;
	}
	spl_autoload_register('DangerFrame_AutoLoad');
	//foreach(glob(dirname(__FILE__) . "/*.class.php") AS $file)
	//	include $file;
	
	DangerFrame_Builder::start();
	
?>