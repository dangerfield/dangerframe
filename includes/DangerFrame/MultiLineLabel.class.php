<?php
class DangerFrame_MultiLineLabel extends DangerFrame_WebComponent
{
	protected $text;
	public function __construct($id, $text)
	{
		parent::__construct($id);
		$this->text = $text;
	}
	public function render()
	{
		$this->removeChildren();
		foreach(self::splitIntoParagraphs($this->text) AS $paragraph)
		{
			$lines = self::splitIntoLines($paragraph);
			
			$p = DangerFrame_Builder::getDOMDocument()->createElement('p');
			$p->appendChild(new DomText(trim($lines[0])));
			
			foreach(array_slice($lines,1) AS $line)
			{
				$p->appendChild(DangerFrame_Builder::getDOMDocument()->createElement('br'));
				$p->appendChild(new DomText(trim($line)));
			}
			
			$this->appendChild($p);	
		}
	}
	protected static function splitIntoParagraphs($text)
	{
		return preg_split('/(\s*\n\s*){2,}/',$text);
	}
	protected static function splitIntoLines($text)
	{
		return preg_split('/(\s*\n\s*)/',$text);
	}

}