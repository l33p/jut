<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
jimport('joomla.application.module.helper');


class JutViewCpanel extends JutView
{
	function display($tpl = null)
	{
		// Document title
		JToolBarHelper::title(JUT_NAME .' '. JUT_VERSION_READ, 'jut');
		JToolBarHelper::help('screen.cpanel');
		
		$doc	= JFactory::getDocument();
		$doc->addStyleDeclaration(
			'.icon-48-jut{'.
				'background-image:url(components/com_ubar/assets/img/butterfly-48.png);'.
			'}'.
			'.icon-32-export{'.
				'background-image:url(templates/khepri/images/toolbar/icon-32-export.png);'.
			'}'.
			
			// cpanel icons
			'#cpanel div.icon a {'.
				'height: 80px;'.
				'width: 114px;'.
			'}'
		);
		
		parent::display($tpl);
	}
	
	/*
	 * cPanel helper
	 */
	function cPanelButton($name, $img, $link)
	{
		// Build link
		$lo	= explode(':', $link);
		switch ($lo[0])
		{
			case 'view':
				$href	= index .'&view='. $lo[1];
				break;
			
			case 'task':
				$href	= index .'&task='. $lo[1] .':'. $lo[2];
				break;
			
			default:
				$href	= index;
		}
		
		// Return button HTML
		return
		'<div class="icon">'.
			'<a href="'. $href .'">'.
				'<img src="'. $img .'" alt="[icon]" />'.
				'<span>'. $name .'</span>'.
			'</a>'.
		'</div>';
	}
}

