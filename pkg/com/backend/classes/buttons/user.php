<?php
defined('_JEXEC') or die;

// Parent class
require_once(UBAR_CLASSES.DS.'buttons'.DS.'base.php');

/*
 * Button Type: User
 */
class uBarBtnUser extends uBarBtnRenderer
{
	function render()
	{
		// Performance check
		if (!$this->isIncluded()) {
			return '';
		}
		
		// Guest button (login)
		$user	= & JFactory::getUser();
		if ($user->guest) {
			return $this->renderGuestButton();
		}
		
		// Registered user button
		return $this->renderUserButton();
	}
	
	function renderGuestButton()
	{
		// Login URL
		$url	= $this->getUrl($this->params->get('login', ''));
		
		// Script
		return $this->getScript('addLink', array(
			$this->getJsObj($this->getButton()),
			'"'. urlencode($url) .'"'
		));
	}
	
	function renderUserButton()
	{
		// Script
		// ToDo: use bUser script??
		return $this->getScript('addMenu', array(
			$this->getJsObj($this->getButton()),
			$this->getJsObj($this->getMenuLinks(), true)
		));
	}
	function insertRegisteredButton()
	{
		$b	= array();
		$b['message']	= JText::_('UT_USER_DESC', true);
		$b['logoutUrl']	= $this->logoutURL;
		$b['logoutText']	= JText::_('LOGOUT', true);
		
		// User links
		$js	= '';
		$links	= $this->getUserURLs();
		foreach ($links as $link) {
			$js	.=
			'bUser.addLink('.
				$this->getJsObj(array(
					'text'	=> $link[0]
				)) .',"'. urlencode($link[1]) .'"'.
			');';
		}
		
		// Button script
		$js	.= 'bUser.insertButton('. $this->getJsObj($b) .');';
		$this->insertScript($this->file, $js);
	}
	
	function setLoginURLs()
	{
		// Login
		$r	= base64_encode($this->uri->toString());
		$this->loginURL	= $this->uri->toString(array('scheme', 'host', 'port'));
		$this->loginURL	.= JRoute::_('index.php?option=com_user&view=login&return='. $r, false);
		
		// Logout
		$this->logoutURL	= 'index.php?option=com_user&task=logout&return='. $r;
	}
	
	function getUserURLs()
	{
		// User component (default)
		return $this->_getUserURLs();
	}
	
	function _getUserURLs()
	{
		$details	= JRoute::_('index.php?option=com_user&task=edit', false);
		
		return array(
			
			// User details
			array(
				JText::_('EDIT_DETAILS', true),
				$this->_shp . $details
			)
		);
	}
}

