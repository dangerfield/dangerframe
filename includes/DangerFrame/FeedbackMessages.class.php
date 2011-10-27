<?php
class DangerFrame_FeedbackMessages
{
	private $messages;
	public function __construct()
	{
		$this->messages = new DangerFrame_List();
	}
	public function add($a, $b = null, $c = null)
	{
		if($a instanceof DangerFrame_Component)
			$this->addComponent($a, $b, $c);
		else if($a instanceof DangerFrame_FeedbackMessage)
			$this->addFeedbackMessage($a);
		else
			throw new RuntimeException;
	}
	private function addComponent(DangerFrame_Component $reporter, $message, $level)
	{
		$this->add(new DangerFrame_FeedbackMessage($reporter, $message, $level));
	}
	private function addFeedbackMessage(DangerFrame_FeedbackMessage $message)
	{
		$this->messages->append($message);
	}
	
	public function clear(DangerFrame_IFeedbackMessageFilter $filter = null)
	{
		if($this->messages->count() == 0)
			return 0;
		$toDelete = $this->messages($filter);
		foreach($toDelete AS $message)
			$message->detach();
		$messages->removeAll($toDelete);
		return $toDelete->count();
	}
	
	public function debug(DangerFrame_Component $reporter, $message)
	{
		$this->add(new DangerFrame_FeedbackMessage($reporter, $message, DangerFrame_FeedbackMessage::DEBUG));
	}
	
	public function error(DangerFrame_Component $reporter, $message)
	{
		$this->add(new DangerFrame_FeedbackMessage($reporter, $message, DangerFrame_FeedbackMessage::ERROR));
	}
	
	public function hasErrorMessageFor(DangerFrame_Component $component)
	{
		return $this->hasMessageFor($component, DangerFrame_FeedbackMessage::ERROR);
	}
	public function hasMessage(DangerFrame_IFeedbackMessageFilter $filter)
	{
		return $this->messages($filter)->count() != 0;
	}
	public function hasMessageFor(DangerFrame_Component $component, $level = null)
	{
		if(is_null($level))
			return !is_null($this->messageForComponent($component));
		else
		{
			foreach($this->messages AS $message)
			{
				if($message->getReporter() == $component && $message->isLevel($level))
					return true;
			}
			return false;
		}
	}
	public function info(DangerFrame_Component $reporter, $message)
	{
		$this->add(new DangerFrame_FeedbackMessage($reporter, $message, DangerFrame_FeedbackMessage::INFO));
	}
	public function isEmpty()
	{
		return $this->messages->isEmpty();
	}
	public function getIterator()
	{
		return $this->messages->getIterator();
	}
	public function messageForComponent(DangerFrame_Component $component)
	{
		foreach($this->messages AS $message)
			if($message->getReporter() == $component)
				return $message;
		return null;
	}
	public function messages(DangerFrame_IFeedbackMessageFilter $filter)
	{
		if($this->messages->count() == 0)
			return new DangerFrame_List();
		$list = new ArrayObject();
		foreach($this->messages AS $message)
			if(is_null($filter) || $filter->accept($message))
				$list->append($message);
		return $list;
	}
	public function size(DangerFrame_IFeedbackMessageFilter $filter)
	{
		if(is_null($filter))
			return $this->messages->count();
		else
		{
			$count = 0;
			foreach($this->messages AS $message)
				if(is_null($filter) || $filter->accept($message))
					$count++;
			return $count;
		}
	}
	public function __toString()
	{
		return "[feedbackMessages = " . $this->messages . "]";
	}
	public function warn(DangerFrame_Component $reporter, $message)
	{
		$this->add(new DangerFrame_FeedbackMessage($reporter, $message, DangerFrame_FeedbackMessage::WARNING));
	}
}
?>