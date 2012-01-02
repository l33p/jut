<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Close button
 */
class uBarBtnHide extends uBarBtnRenderer
{
	var $id		= 'uBarBtnClose';
	
	function render()
	{
		$this->icon	= $this->params->get('close-icon', 'cross.png');
		
		return $this->getScript('addButton', array(
			$this->getJsObj($this->getButton()),
			'UserToolbar.hideToolbar.bind(UserToolbar)'
		));
	}
}

