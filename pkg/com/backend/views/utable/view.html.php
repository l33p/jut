<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class UbarViewUtable extends UbarView
{
	function display($tpl = null)
	{
		// Toolbar
		JToolBarHelper::customX('update', 'forward', 'forward', 'Update', false);
		JToolBarHelper::title('Update Database', 'cpanel');
		
		// Sub menu
		$disabled	= 'javascript:alert(\'Update your database first!\');';
		JSubMenuHelper::addEntry(JText::_('BUTTONS'), $disabled);
		JSubMenuHelper::addEntry(JText::_('CONFIGURATION'), $disabled);
		
		// Document title
		$this->setTitle('Update Database');
		
		// Scripts
		JHTML::_('behavior.keepalive');
		
		// References
		
		
		parent::display($tpl);
	}
}

