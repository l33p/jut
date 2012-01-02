<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');


class UbarModelInstall extends UbarModel
{
	function UbarModelInstall() {
		parent::__construct();
	}
	
	// Install default buttons
	function install()
	{
		// Information button
		if (!$this->infoBtn()) {
			return false;
		}
		
		// Search button
		if (!$this->srchBtn()) {
			return false;
		}
		
		// Notepad
		if (!$this->padBtn()) {
			return false;
		}
		
		// User button
		if (!$this->userBtn()) {
			return false;
		}
		
		// Feed link
		if (!$this->feedBtn()) {
			return false;
		}
		
		// Linkback
		if (!$this->ubarBtn()) {
			return false;
		}
		
		return true;
	}
	
	// Return empty button
	function getButton()
	{
		$table	= $this->getTable('Btn');
		$table->id	= 0;
		$table->reset();
		
		return $table;
	}
	
	// Save button
	function save($btn) {
		return $btn->save(array()) ? true : $this->setError($btn->getError());
	}
	
	// Information button
	function infoBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'Info';
		$btn->desc	= 'About this tool';
		$btn->icon	= 'components/com_ubar/assets/img/icons/information.png';
		$btn->access	= 0;
		$btn->ordering	= 1;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('type', 'html');
		
		// HTML
		global $mainframe;
		$site	= $mainframe->getCfg('sitename');
		$btn->misc	=
		'<div style="letter-spacing:2px;text-align:left;">'.
			'This toolbar was meant to help you navigate<br />'.
			'this site. It provides tools and shortcuts<br />'.
			'to enhance your browsing experience.'.
		'</div>'.
		'<div style="margin:10px 0 0 0;text-align:right;">'.
			'<strong>'.
				'uBar '. UBAR_VERSION_READ .
				' &copy; '. date('Y') .
			'</strong><br/>'.
			'<span style="font-style:italic">'. $site .'</span>'.
		'</div>';
		JRequest::setVar('misc', $btn->misc, 'POST');
		
		// Save button
		return $this->save($btn);
	}
	
	// Search button
	function srchBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'Search';
		$btn->desc	= 'Highlight some words in the page and click the button';
		$btn->icon	= 'components/com_ubar/assets/img/icons/zoom.png';
		$btn->access	= 0;
		$btn->ordering	= 2;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('type', 'search');
		$btn->setParam('action', 'index.php?option=com_search&task=search');
		$btn->setParam('method', 'POST');
		$btn->setParam('input', 'searchword');
		
		// Save button
		return $this->save($btn);
	}
	
	// Notepad
	function padBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'Notepad';
		$btn->desc	= 'Notepad (click to open, click to save)';
		$btn->icon	= 'components/com_ubar/assets/img/icons/note.png';
		$btn->access	= 0;
		$btn->ordering	= 3;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('w', 345);
		$btn->setParam('h', 135);
		$btn->setParam('type', 'pad');
		
		// Save button
		return $this->save($btn);
	}
	
	// User button
	function userBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'User button';
		$btn->desc	= 'User tasks and shortcuts';
		$btn->icon	= 'components/com_ubar/assets/img/icons/user.png';
		$btn->access	= 0;
		$btn->ordering	= 4;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('type', 'user');
		$btn->setParam('login', 'index.php?option=com_user&view=login&return=[url-64]');
		
		// User links
		$lks	= array();
		$lks[]	= 't=Edit%20your%20details&u=index.php%3Foption%3Dcom_user%26view%3Duser%26task%3Dedit&x=_self';
		$lks[]	= 't=Logout&u=index.php%3Foption%3Dcom_user%26view%3Dlogin&x=_self';
		$btn->misc	= implode("\n", $lks);
		
		// Save button
		return $this->save($btn);
	}
	
	// Feed button
	function feedBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'Feed';
		$btn->desc	= 'RSS Feed';
		$btn->icon	= 'components/com_ubar/assets/img/icons/feed.png';
		$btn->access	= 0;
		$btn->ordering	= 5;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('type', 'link');
		$btn->setParam('url', JURI::root() .'?format=feed');
		$btn->setParam('target', '_blank');
		
		// Save button
		return $this->save($btn);
	}
	
	// Linkback button
	function ubarBtn()
	{
		// Details
		$btn	= $this->getButton();
		$btn->name	= 'uBar';
		$btn->desc	= '';
		$btn->icon	= 'components/com_ubar/assets/img/icons/butterfly.png';
		$btn->access	= 0;
		$btn->ordering	= 6;
		$btn->published	= 1;
		
		// Parameters
		$btn->setParam('type', 'link');
		$btn->setParam('url', 'http://j.l33p.com/ubar');
		$btn->setParam('target', '_blank');
		
		// Save button
		return $this->save($btn);
	}
}

