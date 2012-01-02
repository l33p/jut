<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class JutView extends JView
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setTitle();
	}
	
	function display($tpl = null)
	{
		parent::display($tpl);
		
	}
	
	function setTitle($title = '', $icon = null)
	{
		// Page title
		$doc	= JFactory::getDocument();
		$title	= JString::strlen($title) ? $title .' - ' : '';
		$doc->setTitle($title . JUT_NAME);
		
		// Toolbar title
		if ($icon) {
			JToolBarHelper::title($title . JUT_NAME, $icon);
		}
	}
	
	// Returns editor HTML
	function getEditor($text, $name = 'text') {
		$editor	= JFactory::getEditor();
		return $editor->display($name, $text, '100%', '400', '70', '15');
	}
}

