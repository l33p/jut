<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Other Link
 */
class uBarBtnLink extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// URL
		$url	= $this->getUrl($this->params->get('url', ''));
		
		// URL options
		$pop	= array();
		if (strlen($this->params->get('w'))) {
			$pop['width']	= $this->params->get('w');
			$pop['height']	= $this->params->get('h');
		}
		$opts	= $this->getUrlOptions($this->params->get('target'), $pop);
		
		// Script
		return $this->getScript('addLink', array(
			$this->getJsObj($this->getButton()),
			'"'. urlencode($url) .'"', $opts
		));
	}
}

