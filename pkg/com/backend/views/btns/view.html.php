<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class UbarViewBtns extends UbarView
{
	function display($tpl = null)
	{
		// Toolbar
		JToolBarHelper::customX('install', 'export', 'export', 'DEFAULT', false);
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::customX('copy', 'copy', 'copy', 'COPY', true);
		JToolBarHelper::deleteListX('VALIDDELETEITEMS');
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::title('uBar '. UBAR_VERSION_READ, 'buttons');
		
		// Sub menu
		JSubMenuHelper::addEntry(JText::_('BUTTONS'), index .'&view=btns', true);
		JSubMenuHelper::addEntry(JText::_('CONFIGURATION'), index .'&view=config');
		
		// Document title
		$this->setTitle(JText::_('BUTTONS'));
		
		$doc	= & JFactory::getDocument();
		$doc->addStyleDeclaration(
			'.icon-48-buttons{'.
				'background-image:url(components/com_ubar/assets/img/butterfly-48.png);'.
			'}'.
			'.icon-32-export{'.
				'background-image:url(templates/khepri/images/toolbar/icon-32-export.png);'.
			'}'
		);
		
		$this->assignRef('btns', $this->get('Buttons'));
		$this->assignRef('page', $this->get('Pagination'));
		$this->assignRef('order', $this->get('Order'));
		$this->assignRef('plgParams', $this->get('Params'));
		
		parent::display($tpl);
	}
}

