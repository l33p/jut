<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: Page Search
 */
class uBarBtnSearch extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Script file
		$this->addScript('search');
		
		// Button details
		$btn	= $this->getButton();
		$btn['method']	= $this->params->get('method', 'get');
		$btn['target']	= $this->params->get('target', '_self');
		$btn['input']	= $this->params->get('input', 'searchword');
		
		// Form values
		$uri	= JURI::getInstance($this->params->get('action', 'index.php?option=com_search&task=search'));
		$btn['hidden']	= $uri->getQuery(true);
		
		// Form action
		$uri->setQuery(array());
		$btn['action']	= $uri->toString();
		
		// Script
		return 'uBarSearch.add('. $this->getJsObj($btn) .');';
	}
}

