<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');


class JutController extends JController
{
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	function display()
	{
		// Check table
		/*if (!$this->checkTable()) {
			return $this->setRedirect(index .'&view=utable');
		}*/
		
		// Default view
		if (!JRequest::getVar('view')) {
			JRequest::setVar('view', 'cpanel');
		}
		
		return parent::display();
	}
	
	function cancel($r = null) {
		$r	= $r ? $r : INDEX;
		$this->setRedirect($r, JText::_('NOTICE CANCELLED'));
	}
}
