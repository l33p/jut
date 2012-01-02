<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class UbarViewBtn extends UbarView
{
	function UbarViewBtn()
	{
		parent::__construct();
		
		
	}
	
	function display($tpl = null)
	{
		// New
		$b	= & $this->get('Button');
		$new	= ($b->id < 1);
		
		// Layout
		$l	= JRequest::getWord('layout');
		$l	= strlen($l) ? ($l == 'edit') : strlen($b->getParam('type'));
		$l ? $this->form($new) : $this->type($new);
		
		$this->assignRef('btn', $b);
		
		$doc	= & JFactory::getDocument();
		$doc->addStyleDeclaration(
			'.icon-48-component{'.
				'background-image:url(templates/khepri/images/header/icon-48-component.png);'.
			'}'
		);
		
		parent::display($tpl);
	}
	
	function type($isNew = true)
	{
		$this->setLayout('type');
		
		// Document title
		$this->setTitle(JText::_('BUTTON') .' ('. JText::_('TYPE') .')');
		
		// Toolbar
		JToolBarHelper::title(JText::_('BUTTON') .': <small><small>[ '. JText::_('NEW') .' ]</small></small>', 'component');
		JToolBarHelper::cancel();
		
		// Scripts
		// See: "com_menus\views\item\view.php"
		$lang	= & JFactory::getLanguage();
		$css	= $lang->isRTL() ? 'type_rtl.css' : 'type.css';
		JHTML::stylesheet($css, 'administrator/components/com_menus/assets/');
		
		// References
		$this->assignRef('expansion', $this->get('TreeExpansion'));
		$this->assignRef('componentList', $this->get('ComponentList'));
		$this->assignRef('moduleList', $this->get('ModuleList'));
	}
	
	function form($isNew = true)
	{
		$this->setLayout('edit');
		
		// Document title
		$this->setTitle(JText::_('BUTTON'));
		
		// Toolbar
		$b	= & $this->get('Button');
		$title 	= $isNew ? ($b->get('isCopy') ? JText::_('COPY') : JText::_('NEW')) : JText::_('EDIT');
		JToolBarHelper::title(JText::_('BUTTON') .': <small><small>[ '. $title .' ]</small></small>', 'component');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$isNew ? JToolBarHelper::cancel() : JToolBarHelper::cancel('cancel', JText::_('CLOSE'));
		
		// Scripts
		JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.keepalive');
		
		// Component
		if ($b->getParam('type') == 'component') {
			$this->assignRef('urlParams', $this->get('UrlParams'));
		}
		
		// Module
		elseif ($b->getParam('type') == 'module') {
			$this->assignRef('modParams', $this->get('ModuleParams'));
		}
		
		// Slider pane
		jimport('joomla.html.pane');
		$pane	= & JPane::getInstance('sliders');
		$this->assignRef('pane', $pane);
	}
	
	/*
	 * Renders ordering field
	 */
	function getOrdering($btn)
	{
		// New button
		if ($btn->id < 1) {
			return '<input type="hidden" name="ordering" value="0" />'. JText::_('DESCNEWITEMSLAST');
		}
		
		// Order list
		$order	= JHTML::_('list.genericordering',
			'SELECT ordering AS value, name AS text '.
			'FROM #__ubar_btn ORDER BY ordering'
		);
		
		// Return ordering
		return JHTML::_('select.genericlist', $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', (int) $btn->ordering);
	}
	
	/*
	 * Renders menu select list
	 */
	function getMenuSelect($name, $selected = null)
	{
		// Menu list
		$db	= & JFactory::getDBO();
		$db->setQuery(
			'SELECT id AS value, title AS text '.
			'FROM #__menu_types ORDER BY title'
		);
		
		// Select
		$list	= (array) $db->loadObjectList();
		return JHTML::_('select.genericlist', $list, $name, 'class="inputbox" size="1"', 'value', 'text', $selected);
	}
}

