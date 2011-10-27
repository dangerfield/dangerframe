<?php
class DangerFrame_Builder
{
	protected static $preBuffer;
	protected static $p, $d, $cwd;
	public static function start()
	{
		self::$p	= basename     ($_SERVER['SCRIPT_FILENAME'], '.php');
		self::$d	= dirname      ($_SERVER['SCRIPT_FILENAME']);
		self::$cwd	= getcwd();

		register_shutdown_function(array(__CLASS__, 'shutdown'));
		
		self::$preBuffer = new DangerFrame_OutputBuffer();
		
		self::getDOMDocument()->formatOutput = true;
	}
	public static function shutdown()
	{
		$xml = self::$preBuffer->getClean();
		
		chdir(self::$cwd);
		
		if(error_get_last()){
			echo $xml;
			echo '<p>Page Execution ended prematurity as PHP encoutered an error before entering page controller</p>';
			return;
		}
		try
		{
		if(self::viewExists(self::$d, self::$p))
			$xml = self::executeView(self::$d, self::$p);
	
		self::getDOMDocument()->loadXML($xml);
				
		if(self::controllerExists(self::$p))
			self::executeController(self::$p);

		}
		catch(Exception $e)
		{
			echo $e;
			exit;
		}
		self::stripDFattributes();

		echo self::getDOMDocument()->saveHTML();
	}
	protected static function controllerExists($name)
	{
		return class_exists(self::$p);
	}
	public static function executeController($name)
	{
		if(!self::controllerExists($name))
			throw new BuilderException('View file not found');
			
		$o = new self::$p;
		$o->initDOM(self::getDOMDocument());
		$o->render();
		
	}
	protected static function viewExists($directory, $name)
	{
		return file_exists($directory . DIRECTORY_SEPARATOR . $name . '.xhtml.php');
	}
	public static function executeView($directory, $name)
	{
		if(!self::viewExists($directory, $name))
			throw new BuilderException('View file not found');
	
		$includeBuffer = new DangerFrame_OutputBuffer();

		require_once($directory . DIRECTORY_SEPARATOR . $name . '.xhtml.php' );

		return $includeBuffer->getClean();

	}
	protected static $DOMDocument;
	public static function getDOMDocument()
	{
		if(is_null(self::$DOMDocument))
			self::$DOMDocument = new DOMDocument;
		return self::$DOMDocument;
	}
	protected static function stripDFattributes()
	{
		$xpath = new DOMXPath(self::getDOMDocument());

		$xpath->registerNamespace('df', 'dangerframe');

	    foreach( $xpath->query("//@*[namespace-uri()='dangerframe']") AS $node )	{
			$node->parentNode->removeAttributeNode($node);
	    }
	}
}
?>