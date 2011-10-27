<?php
class DangerFrame_Label extends DangerFrame_WebComponent
{
	protected $text;
	public function __construct($id, $text)
	{
		parent::__construct($id);
		$this->text = $text;
	}
	public function render()
	{				
		// Strips useless whitespace, converting to a single space. Removal wouldn't effect browser-rendered HTML output, but keeps things tidy in the source!
		$this->removeChildren();
		$this->appendChild(new DomText(preg_replace('/(\s){2,}/',' ',$this->text)));

	}
}

?>