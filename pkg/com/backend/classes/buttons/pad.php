<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Notepad
 */
class uBarBtnPad extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Script file
		static $added;
		if (!$added) {
			$added	= true;
			$this->addScript('notepad');
		}
		
		// Button details
		$b	= $this->getButton();
		$b['w']	= (int) $this->params->get('w', 345);
		$b['h']	= (int) $this->params->get('h', 135);
		
		// Script
		return 'uBarPad.add('. $this->getJsObj($b) .');';
	}
}

