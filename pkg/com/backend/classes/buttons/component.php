<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Internal Link
 */
class uBarBtnComponent extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// URL
		$table	= $this->getTable();
		$url	= $this->getUrl($table->getComponentUrl(true));
		
		// Script
		return $this->getScript('addLink', array(
			$this->getJsObj($this->getButton()),
			'"'. urlencode($url) .'"'
		));
	}
}

