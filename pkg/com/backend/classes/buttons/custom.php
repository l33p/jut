<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Custom
 */
class uBarBtnCustom extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Add script
		$url	= $this->params->get('url', '');
		if (strlen($url)) {
			$this->_doc->addScript($url);
		}
		if (strlen($this->misc)) {
			$this->_doc->addScriptDeclaration($this->misc);
		}
		
		return $this->getScript('addButton', array(
			$this->getJsObj($this->getButton()),
			'function() {'.
				'UTb.closeBoxes();'.
				$this->params->get('clk', '') .
			'}'
		));
	}
}

