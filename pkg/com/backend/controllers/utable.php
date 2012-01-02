<?php
defined('_JEXEC') or die;

class UbarControllerUtable extends UbarController
{
	function UbarControllerUtable() {
		parent::__construct();
	}
	
	function update()
	{
		
		$this->setRedirect(index .'&view=utable', 'ToDo: Fix table');
	}
	
	function cancel() {
		parent::cancel(index .'&view=utable');
	}
}

