<?php
interface DangerFrame_ComponentIVisitor
{
	const CONTINUE_TRAVERSAL = 1;
	const CONTINUE_TRAVERSAL_BUT_DONT_GO_DEEPER = 2;
	const STOP_TRAVERSAL = 4;
	
	public function component(DangerFrame_Component $component);
}
?>