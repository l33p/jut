<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Custom Menu
 */
class uBarBtnMenu extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Script
		return $this->getScript('addMenu', array(
			$this->getJsObj($this->getButton()),
			$this->getJsObj($this->getMenuLinks(), true)
		));
	}
}

