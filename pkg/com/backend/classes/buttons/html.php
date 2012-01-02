<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: HTML
 */
class uBarBtnHtml extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// HTML
		$html	= $this->get('misc', '');
		
		// Script
		return $this->getScript('addHtml', array(
			$this->getJsObj($this->getButton()),
			'"'. urlencode($html) .'"'
		));
	}
}

