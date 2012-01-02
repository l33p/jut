<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');


class JutViewConfig extends JutView
{
	function display($tpl = null)
	{
		// Toolbar
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JToolBarHelper::media_manager(JURI::root(true) .'/components/com_jut/assets/img/');
		
		// Page title
		$this->setTitle(JText::_('CONFIGURATION'), 'jut-config');
		
		// Scripts
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.keepalive');
		$doc	= JFactory::getDocument();
		$doc->addScript(JUT_ASSETS .'js/config.js?'. JUT_VERSION_INC);
		$doc->addStyleDeclaration(
			'.icon-48-jut-config{'.
				'background-image:url(components/com_jut/assets/img/setting_tools_48.png);'.
			'}'.
			'#preview-w {'.
				'width: auto;'.
				'height: 310px;'.
				'background-image: url("'. JUT_ASSETS .'img/browser-screen.png");'.
				'background-color: inherit;'.
				'background-position: center center;'.
				'background-repeat: no-repeat;'.
			'}'
		);
		
		// References
		$this->assignRef('plgParams', $this->get('Params'));
		$this->assign('btns', $this->get('PreviewButtons'));
		
		parent::display($tpl);
	}
}

